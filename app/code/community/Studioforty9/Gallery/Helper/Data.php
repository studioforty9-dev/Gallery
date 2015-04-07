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
 * Studioforty9_Gallery_Helper_Data
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Helper
 */
class Studioforty9_Gallery_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Get the URL to the gallery controller.
     *
     * @return string
     */
    public function getGalleryUrl()
    {
        return $this->_getUrl('gallery');
    }

    public function getUseLink()
    {
        return Mage::getStoreConfigFlag('studioforty9_gallery/default/uselink');
    }

    public function getGalleryName()
    {
        $name = Mage::getStoreConfig('studioforty9_gallery/default/link');

        if (empty($name)) {
            $name = $this->__('Gallery');
        }

        return $name;
    }
    /**
     * Flag to determine whether or not the breadcrumbs should be displayed.
     *
     * @return bool
     */
    public function getUseBreadcrumbs()
    {
        return Mage::getStoreConfigFlag('studioforty9_gallery/default/breadcrumbs');
    }

    /**
     * The seo title for the gallery.
     *
     * @return string
     */
    public function getSeoTitle()
    {
        return Mage::getStoreConfig('studioforty9_gallery/seo/title');
    }

    public function getSeoKeywords()
    {
        return Mage::getStoreConfig('studioforty9_gallery/seo/keywords');
    }

    public function getSeoDescription()
    {
        return Mage::getStoreConfig('studioforty9_gallery/seo/description');
    }

    /**
     * getImagePath
     *
     * @param string $type
     * @return string
     */
    public function getImagePath($type)
    {
        return Mage::getBaseDir('media') . DS . 'gallery' . DS . $type . DS;
    }

    /**
     * getImageUrl
     *
     * @param string $type
     * @return string
     */
    public function getImageUrl($type)
    {
        return Mage::getBaseUrl('media') . sprintf('gallery/%s/', $type);
    }

    /**
     * Format a string into a valid URL key.
     *
     * @param $str
     * @return string
     */
    public function formatUrlKey($str)
    {
        $str = Mage::helper('core')->removeAccents($str);
        $urlKey = preg_replace('#[^0-9a-z]+#i', '-', $str);
        $urlKey = strtolower($urlKey);
        $urlKey = trim($urlKey, '-');
        return $urlKey;
    }
}