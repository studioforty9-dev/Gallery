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
 * Studioforty9_Gallery_Block_Adminhtml_Media_Helper_Image
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Block
 */
class Studioforty9_Gallery_Block_Adminhtml_Media_Helper_Image extends Varien_Data_Form_Element_Image
{
    /**
     * _getUrl()
     *
     * @return bool|string
     */
    protected function _getUrl()
    {
        $url = false;
        $type = (strstr(strtolower($this->getLabel()), 'media')) ? 'media' : 'album';
        $value = $this->getValue();

        if (is_array($value)) {
            $value = $value['value'];
        }

        if (empty($value)) {
            return $url;
        }

        $path = 'gallery/' . $type . '/'. $value;
        $file = Mage::getBaseDir('media') . '/'. $path;
        if (!file_exists($file)) {
            return $url;
        }

        return Mage::getBaseUrl('media') . $path;
    }
}
