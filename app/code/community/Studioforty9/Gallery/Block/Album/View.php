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
 * Studioforty9_Gallery_Block_Album_View
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Block
 */
class Studioforty9_Gallery_Block_Album_View extends Mage_Core_Block_Template
{
    /**
     * Prepare the layout.
     *
     * @return self
     */
    public function _prepareLayout()
    {
        parent::_prepareLayout();

        $helper = $this->helper('studioforty9_gallery');
        /** @var Studioforty9_Gallery_Model_Album $album */
        $album = Mage::registry('current_album');
        $collection = $album->getRelatedMedia();

        /** @var Mage_Page_Block_Html_Pager $pager */
        $pager = $this->getLayout()->createBlock('page/html_pager', 'gallery.album.pager');
        $pager->setAvailableLimit($helper->getMediaPerPageOptions());
        $pager->setCollection($collection);
        $this->setChild('pager', $pager);

        $this->setAlbum($album);
        $this->setMedia($collection);

        return $this;
    }

    /**
     * Get the pagination html.
     *
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}
