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

    /**
     * Flag to determine if we should use a link in the top links.
     *
     * @return bool
     */
    public function getUseLink()
    {
        return Mage::getStoreConfigFlag('studioforty9_gallery/default/uselink');
    }

    /**
     * The gallery name for the label on the link.
     *
     * @return string
     */
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

    /**
     * The default seo keywords.
     *
     * @return string
     */
    public function getSeoKeywords()
    {
        return Mage::getStoreConfig('studioforty9_gallery/seo/keywords');
    }

    /**
     * The default seo description.
     *
     * @return string
     */
    public function getSeoDescription()
    {
        return Mage::getStoreConfig('studioforty9_gallery/seo/description');
    }

    /**
     * Get the album perpage options.
     *
     * @return array
     */
    public function getAlbumPerPageOptions()
    {
        $options = Mage::getStoreConfig('studioforty9_gallery/album/perpage');
        return $this->getCommaSeparatedAsArray($options);
    }

    /**
     * Get the media perpage options.
     *
     * @return array
     */
    public function getMediaPerPageOptions()
    {
        $options = Mage::getStoreConfig('studioforty9_gallery/media/perpage');
        return $this->getCommaSeparatedAsArray($options);
    }

    /* %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% */

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

    /**
     * Turn a comma separated string into an array.
     *
     * @param string $string
     * @return array
     */
    public function getCommaSeparatedAsArray($string)
    {
        if (empty($string)) {
            return array();
        }

        $limits = array();
        foreach (explode(',', $string) as $num) {
            $limits[$num] = $num;
        }

        return $limits;
    }

    /**
     * Get the global wysiwyg configuration for the extension.
     *
     * @return string
     */
    public function getWysiwygConfig()
    {
        return <<<WYSIWYG
if(window.tinyMceWysiwygSetup) {
    tinyMceWysiwygSetup.prototype.originalGetSettings = tinyMceWysiwygSetup.prototype.getSettings;
    tinyMceWysiwygSetup.prototype.getSettings = function(mode) {
        var settings = this.originalGetSettings(mode);
        settings.extended_valid_elements = 'input[placeholder|accept|alt|checked|disabled|maxlength|name|readonly|size|src|type|value]';
        settings.theme_advanced_buttons1 = 'bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,blockquote,outdent,indent,|,formatselect,|,pasteword,|,bullist,numlist,|,link,unlink,anchor,image,|,fontselect,fontsizeselect,fullscreen';
        settings.theme_advanced_buttons2 = '';
        settings.theme_advanced_buttons3 = '';
        settings.theme_advanced_buttons4 = '';
        settings.forced_root_block = false;
        return settings;
    };
}
WYSIWYG;
    }
}
