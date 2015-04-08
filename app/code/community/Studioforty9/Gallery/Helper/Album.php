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
 * Studioforty9_Gallery_Helper_Album
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Helper
 */
class Studioforty9_Gallery_Helper_Album extends Mage_Core_Helper_Abstract
{
    /**
     * Get the album by url key.
     *
     * @param string $urlKey
     * @return bool|Varien_Object
     */
    public function getAlbumByUrlKey($urlKey)
    {
        $collection = Mage::getModel('studioforty9_gallery/album')->getCollection()
            ->addFieldToFilter('url_key', $urlKey);

        if ($collection->count() < 1) {
            return false;
        }

        return $collection->getFirstItem();
    }

    /**
     * Get the url to the album model stored in the registry.
     *
     * @return bool|string
     */
    public function getAlbumUrl()
    {
        /** @var Studioforty9_Gallery_Model_Album $album */
        if (!$album = Mage::registry('current_album')) {
            return false;
        }

        return $album->getUrl();
    }
}
