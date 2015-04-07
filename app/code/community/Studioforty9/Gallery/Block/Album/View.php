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
     * Set the collection on the block before rendering the html.
     */
    public function _beforeToHtml()
    {
        /** @var Studioforty9_Gallery_Model_Album $album */
        $album = Mage::registry('current_album');
        $this->setAlbum($album);
        $this->setMedia($album->getRelatedMedia());
    }
}
