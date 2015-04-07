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
 * Studioforty9_Gallery_Block_Adminhtml_Album_Edit_Tab_Grid
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Block
 */
class Studioforty9_Gallery_Block_Adminhtml_Album_Edit_Tab_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set the default grid configuration in the constructor.
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('albumMediaGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setDefaultFilter(array('selected_media' => '1'));
        //$this->setSaveParametersInSession(false);
        $this->setUseAjax(true);
    }

    /**
     * Prepare the collection.
     *
     * @return Studioforty9_Gallery_Block_Adminhtml_Album_Edit_Tab_Grid
     */
    protected function _prepareCollection()
    {
        $this->setCollection($this->_getCollection());
        return parent::_prepareCollection();
    }

    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() != 'selected_media') {
            return parent::_addColumnFilterToCollection($column);
        }

        $mediaIds = $this->_getSelectedMedia();

        if (empty($mediaIds)) {
            return $this;
        }

        if ($column->getFilter()->getValue() == 1) {
            $this->getCollection()->addFieldToFilter('main_table.entity_id', array('in' => $mediaIds));
        }
        else {
            if ($mediaIds) {
                $this->getCollection()->addFieldToFilter('main_table.entity_id', array('nin' => $mediaIds));
            }
        }

        return $this;
    }

    protected function _getSelectedMedia()
    {
        return array_keys($this->getSelectedMedia());
    }

    /**
     *
     *
     * @return array
     * @throws Exception
     */
    public function getSelectedMedia()
    {
        $data = array();
        $media = $this->getData('selected_media');
        $albumId = $this->getRequest()->getParam('id', 0);

        if ($albumId) {
            if (is_null($media)) {
                $media = $this->_getAlbum()->getSelectedMediaIds();
            }
            $collection = $this->_getCollection()
                ->addFieldToFilter('main_table.entity_id', array('in' => $media));

            foreach ($collection as $medium) {
                $data[$medium->getId()] = array('grid_position' => $medium->getPosition());
            }
        }

        return $data;
    }

    /**
     * Prepare the grid columns.
     *
     * @return Studioforty9_Gallery_Block_Adminhtml_Media_Grid
     */
    protected function _prepareColumns()
    {
        $selected = $this->_getSelectedMedia();
        $this->addColumn('selected_media', array(
            'header_css_class' => 'a-center',
            'type'       => 'checkbox',
            'name'       => 'selected_media',
            'field_name' => 'selected_media',
            'values'     => empty($selected) ? array() : $selected,
            'align'      => 'center',
            'index'      => 'entity_id'
        ));

        $this->addColumn('grid_entity_id', array(
            'header'    => $this->_getHelper()->__('Id'),
            'index'     => 'entity_id',
            'width'    => '50px'
        ));
        $this->addColumn('grid_image', array(
            'header'   => $this->_getHelper()->__('Image'),
            'index'    => 'file',
            'renderer' => 'Studioforty9_Gallery_Block_Adminhtml_Renderer_Image',
            'width'    => '100px'
        ));
        $this->addColumn('grid_name', array(
            'header' => $this->_getHelper()->__('Name'),
            'index'  => 'name',
        ));
        $this->addColumn('grid_status', array(
            'header'    => $this->_getHelper()->__('Status'),
            'index'     => 'status',
            'type'      => 'options',
            'width'     => '100px',
            'options'   => array(
                '1' => $this->_getHelper()->__('Enabled'),
                '0' => $this->_getHelper()->__('Disabled'),
            )
        ));
        $this->addColumn('grid_position', array(
            'header'    => $this->_getHelper()->__('Position'),
            'align'     => 'left',
            'index'     => 'position',
            'field_name' => 'grid_position',
            'width'     => '55px',
            'type'      => 'input',
            'editable'  => true,
            'required'  => true
        ));
    }

    /**
     * Prepare the mass action, specifically, since we're extending the album
     * grid, we need to disable mass actions.
     *
     * @return $this
     */
    protected function _prepareMassaction()
    {
        return $this;
    }

    /**
     * Get the row url.
     *
     * @access public
     * @param Studioforty9_Gallery_Model_Album
     * @return string
     */
    public function getRowUrl($row)
    {
        return '';
    }

    /**
     * Get the grid url.
     *
     * @return string
     */
    public function getGridUrl()
    {
        $url = $this->getUrl('*/gallery_album/mediaGrid', array(
            '_current' => true
        ));

        return $this->_getData('grid_url') ? $this->_getData('grid_url'): $url;
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

    /**
     * @return Studioforty9_Gallery_Model_Album
     * @throws Exception
     */
    protected function _getAlbum()
    {
        if (! Mage::registry('current_album')) {
            $albumId = $this->getRequest()->getParam('id');
            $album = Mage::getModel('studioforty9_gallery/album')->load($albumId);
            Mage::register('current_album', $album);
        }

        return Mage::registry('current_album');
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

    protected function _getCollection()
    {
        /** @var Studioforty9_Gallery_Model_Resource_Media_Collection $collection */
        $collection = Mage::getModel('studioforty9_gallery/media')->getCollection();
        $albumCount = Mage::getModel('studioforty9_gallery/album')->getCollection()->count();

        if ($albumCount > 0) {
            $entityId = $this->_getAlbum()->getId();
            $collection->getSelect()->joinLeft(
                array('pivot' => $collection->getTable('studioforty9_gallery/gallery_media_album')),
                'pivot.media_id=main_table.entity_id AND pivot.album_id=' . (!empty($entityId) ? $entityId : 0),
                'pivot.position'
            );
            $collection->addOrder('pivot.position', 'ASC');
            $this->setDefaultSort('grid_position');
            $this->setDefaultFilter(array('selected_media' => '1'));
        }

        return $collection;
    }
}
