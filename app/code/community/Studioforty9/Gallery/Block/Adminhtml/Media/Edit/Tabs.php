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
 * Studioforty9_Gallery_Block_Adminhtml_Media_Edit_Tabs
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Block
 */
class Studioforty9_Gallery_Block_Adminhtml_Media_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     *
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setId('media_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle($this->_getHelper()->__('Media'));
    }

    /**
     * _beforeToHtml()
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'   => $this->_getHelper()->__('Media Content'),
            'title'   => $this->_getHelper()->__('Media Content'),
            'content' => $this->getLayout()->createBlock(
                'studioforty9_gallery/adminhtml_media_edit_tab_form'
            )->toHtml(),
        ));

        $this->addTab('meta_section', array(
            'label'   => $this->_getHelper()->__('SEO Content'),
            'title'   => $this->_getHelper()->__('SEO Content'),
            'content' => $this->getLayout()->createBlock(
                'studioforty9_gallery/adminhtml_media_edit_tab_meta'
            )->toHtml(),
        ));

        return parent::_beforeToHtml();
    }

    /**
     * _getHelper()
     *
     * @return Studioforty9_Gallery_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('studioforty9_gallery');
    }
}
