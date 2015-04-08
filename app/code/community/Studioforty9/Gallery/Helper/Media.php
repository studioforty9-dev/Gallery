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
 * Studioforty9_Gallery_Helper_Media
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Helper
 */
class Studioforty9_Gallery_Helper_Media extends Mage_Core_Helper_Abstract
{
    /**
     * Get the media by url key.
     *
     * @param string $urlKey
     * @return bool|Varien_Object
     */
    public function getMediaByUrlKey($urlKey)
    {
        $collection = Mage::getModel('studioforty9_gallery/media')->getCollection()
            ->addFieldToFilter('url_key', $urlKey);

        if ($collection->count() < 1) {
            return false;
        }

        return $collection->getFirstItem();
    }

    /**
     * Get the url to the media model stored in the registry.
     *
     * @return bool|string
     */
    public function getMediaUrl()
    {
        /** @var Studioforty9_Gallery_Model_Media $media */
        if (!$media = Mage::registry('current_media')) {
            return false;
        }

        return $media->getUrl();
    }
}
