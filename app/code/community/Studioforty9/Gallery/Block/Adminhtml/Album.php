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
 * Studioforty9_Gallery_Block_Adminhtml_Album
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Block
 */
class Studioforty9_Gallery_Block_Adminhtml_Album extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /** @var string */
    protected $_controller = 'adminhtml_album';

    /** @var string */
    protected $_blockGroup = 'studioforty9_gallery';

    /**
     * Set up the Grid Page.
     */
    protected function _construct()
    {
        parent::_construct();

        $this->_headerText = $this->_getHelper()->__('Albums');

        // Add the ass album button
        $this->_updateButton('add', 'label', $this->_getHelper()->__('Add Album'));

        // Add the sort order save button
        $this->_addButton('position', array(
            'label'     => $this->_getHelper()->__('Save Sort Order'),
            'class'     => 'sort',
            'id'        => 'sort-position'
        ), -1);
    }

    /**
     * Fetch the module helper.
     *
     * @return Studioforty9_Gallery_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('studioforty9_gallery');
    }
}
