<?php
/**
 * Studioforty9 Gallery
 *
 * @category  Studioforty9
 * @package   Studioforty9_Gallery
 * @author    StudioForty9 <info@studioforty9.com>
 * @copyright 2015 StudioForty9 (http://www.studioforty9.com)
 * @license   https://github.com/studioforty9/gallery/blob/master/LICENCE BSD
 * @version   1.0.0
 * @link      https://github.com/studioforty9/gallery
 */

/**
 * Studioforty9_Gallery_Block_Adminhtml_Album_Grid
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Block
 */
class Studioforty9_Gallery_Block_Adminhtml_Album_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set the default grid configuration in the constructor.
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('galleryAlbumGrid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        //$this->setSaveParametersInSession(false);
        $this->setUseAjax(true);
    }

    /**
     * Prepare the collection.
     *
     * @return this
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('studioforty9_gallery/album')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare the grid columns.
     *
     * @return Studioforty9_Gallery_Block_Adminhtml_Position_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => $this->_getHelper()->__('Id'),
            'index'     => 'entity_id',
            'type'      => 'number'
        ));
        $this->addColumn('thumbnail', array(
            'header'   => $this->_getHelper()->__('Thumbnail'),
            'index'    => 'thumbnail',
            'renderer' => 'Studioforty9_Gallery_Block_Adminhtml_Renderer_Image',
            'width'    => '100px',
            'filter_condition_callback'=> array($this, '_filterStoreCondition')
        ));
        $this->addColumn('name', array(
            'header' => $this->_getHelper()->__('Name'),
            'index'  => 'name',
        ));
        $this->addColumn('url_key', array(
            'header' => $this->_getHelper()->__('URL key'),
            'index'  => 'url_key',
        ));
        if (!Mage::app()->isSingleStoreMode() && !$this->_isExport) {
            $this->addColumn('store_id', array(
                'header'=> $this->_getHelper()->__('Store Views'),
                'index' => 'store_id',
                'type'  => 'store',
                'store_all' => true,
                'store_view'=> true,
                'sortable'  => false,
                'filter_condition_callback'=> array($this, '_filterImageCondition'),
            ));
        }
        $this->addColumn('position', array(
            'header'    => $this->_getHelper()->__('Position'),
            'align'     => 'left',
            'index'     => 'position',
            'width'     => '55px',
            'type'      => 'input',
            'editable'  => true
        ));
        $this->addColumn('status', array(
            'header'    => $this->_getHelper()->__('Status'),
            'index'     => 'status',
            'type'      => 'options',
            'width'     => '100px',
            'options'   => array(
                '1' => $this->_getHelper()->__('Enabled'),
                '0' => $this->_getHelper()->__('Disabled'),
            )
        ));
        $this->addColumn('created_at', array(
            'header'    => $this->_getHelper()->__('Created at'),
            'index'     => 'created_at',
            'width'     => '120px',
            'type'      => 'datetime',
        ));
        $this->addColumn('updated_at', array(
            'header'    => $this->_getHelper()->__('Updated at'),
            'index'     => 'updated_at',
            'width'     => '120px',
            'type'      => 'datetime',
        ));
        $this->addColumn('action',
            array(
             'header'=> $this->_getHelper()->__('Action'),
             'width' => '100',
             'type'  => 'action',
             'getter'=> 'getId',
             'actions'   => array(
                 array(
                     'caption' => $this->_getHelper()->__('Edit'),
                     'url'     => array('base' => '*/*/edit'),
                     'field'   => 'id'
                 )
             ),
             'filter'=> false,
             'is_system'    => true,
             'sortable'  => false,
            )
        );

        //$this->addExportType('*/*/exportCsv', $this->_getHelper()->__('CSV'));
        //$this->addExportType('*/*/exportExcel', $this->_getHelper()->__('Excel'));
        //$this->addExportType('*/*/exportXml', $this->_getHelper()->__('XML'));

        return parent::_prepareColumns();
    }

    /**
     * Prepare mass action.
     *
     * @return Studioforty9_Gallery_Block_Adminhtml_Position_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('albums');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'   => $this->_getHelper()->__('Delete'),
            'url'     => $this->getUrl('*/*/massDelete'),
            'confirm' => $this->_getHelper()->__('Are you sure?')
        ));

        $this->getMassactionBlock()->addItem('status', array(
            'label'      => $this->_getHelper()->__('Change status'),
            'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                'status' => array(
                    'name'   => 'status',
                    'type'   => 'select',
                    'class'  => 'required-entry',
                    'label'  => $this->_getHelper()->__('Status'),
                    'values' => array(
                        '1' => $this->_getHelper()->__('Enabled'),
                        '0' => $this->_getHelper()->__('Disabled'),
                    )
                )
            )
        ));

        return $this;
    }

    /**
     * Get the row url.
     *
     * @param Studioforty9_Gallery_Model_Album
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * Get the grid url.
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    /**
     * After collection load
     *
     * @return Studioforty9_Gallery_Block_Adminhtml_Position_Grid
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _filterImageCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->addFieldToFilter('thumbnail', array('like' => $value));
        return $this;
    }

    /**
     * Filter store column
     *
     * @param Studioforty9_Gallery_Model_Resource_Position_Collection $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return Studioforty9_Gallery_Block_Adminhtml_Position_Grid
     */
    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->addStoreFilter($value);
        return $this;
    }

    /**
     * Return the module helper.
     *
     * @return Studioforty9_Gallery_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('studioforty9_gallery');
    }

    public function getAdditionalJavaScript()
    {
        $url = $this->getUrl('*/*/sort');
        $js = parent::getAdditionalJavaScript();
        $js .= <<<SCRIPT
document.observe('dom:loaded', function() {
    // Listen for the success callback
    varienGlobalEvents.attachEventHandler(
        'studioforty9.gallery.grid.sorter.update.success',
        function(args) {
            galleryAlbumGridJsObject.reload();
        }
    );
    var galleryUpdatePosition = function(event) {
        event.stop();
        var albumGridSorter = new GridSorter('galleryAlbumGrid_table');
        albumGridSorter.save('$url');
    };
    Element.observe('sort-position', 'click', galleryUpdatePosition);
});
SCRIPT;

        return $js;
    }
}
