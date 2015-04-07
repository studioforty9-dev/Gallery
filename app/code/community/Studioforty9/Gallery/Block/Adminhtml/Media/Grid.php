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
 * Studioforty9_Gallery_Block_Adminhtml_Media_Grid
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Block
 */
class Studioforty9_Gallery_Block_Adminhtml_Media_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set the default grid configuration in the constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('galleryMediaGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * Prepare the collection.
     *
     * @return Studioforty9_Gallery_Block_Adminhtml_Media_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('studioforty9_gallery/media')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare the grid columns.
     *
     * @return Studioforty9_Gallery_Block_Adminhtml_Media_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => $this->_getHelper()->__('Id'),
            'index'     => 'entity_id',
            'type'      => 'number'
        ));
        $this->addColumn('image', array(
            'header'   => $this->_getHelper()->__('Image'),
            'index'    => 'file',
            'renderer' => 'Studioforty9_Gallery_Block_Adminhtml_Renderer_Image',
            'width'    => '100px'
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
                'filter_condition_callback'=> array($this, '_filterStoreCondition'),
            ));
        }
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
                'header'    => $this->_getHelper()->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption' => $this->_getHelper()->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
                'sort' => 1000
            )
        );

        //$this->addExportType('*/*/exportCsv', $this->_getHelper()->__('CSV'));
        //$this->addExportType('*/*/exportExcel', $this->_getHelper()->__('Excel'));
        //$this->addExportType('*/*/exportXml', $this->_getHelper()->__('XML'));

        return parent::_prepareColumns();
    }

    /**
     * Prepare mass action area.
     *
     * @return Studioforty9_Gallery_Block_Adminhtml_Media_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('media');
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
                    // TODO: add data from helper
                    'values' => array(
                        '1' => $this->_getHelper()->__('Enabled'),
                        '0' => $this->_getHelper()->__('Disabled'),
                    )
                )
            )
        ));
        $albums = Mage::getModel('studioforty9_gallery/album')->getCollection()->toOptionArray();
        $this->getMassactionBlock()->addItem('album', array(
            'label'      => $this->_getHelper()->__('Relate to album'),
            'url'        => $this->getUrl('*/*/massRelation', array('_current'=>true)),
            'additional' => array(
                'album' => array(
                    'name'   => 'album',
                    'type'   => 'select',
                    'class'  => 'required-entry',
                    'label'  => $this->_getHelper()->__('Album'),
                    'values' => $albums
                )
            )
        ));

        return $this;
    }

    /**
     * Get the row url.
     *
     * @access public
     * @param Studioforty9_Gallery_Model_Position
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
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    /**
     * After collection load.
     *
     * @return Studioforty9_Gallery_Block_Adminhtml_Media_Grid
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    /**
     * Filter store column.
     *
     * @param Studioforty9_Gallery_Model_Resource_Media_Collection $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return Studioforty9_Gallery_Block_Adminhtml_Media_Grid
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
}
