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
 * Studioforty9_Gallery_Block_Adminhtml_Renderer_Image
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Block
 */
class Studioforty9_Gallery_Block_Adminhtml_Renderer_Image
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * render()
     *
     * @param Varien_Object $row
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $index = $this->getColumn()->getIndex();
        $value = $row->getData($index);
        $type = strtolower($this->getColumn()->getHeader()) === 'image' ? 'media' : 'album';

        if (empty($value) || !$this->_imageExists($value)) {
            return 'None';
        }

        return sprintf(
            '<p style="text-align:center;padding-top:5px;"><img src="%s/gallery/%s/%s" style="width:100px;"/></p>',
            Mage::getBaseUrl('media'),
            $type,
            $value
        );
    }

    /**
     * imageExists()
     *
     * @param $image
     * @return bool
     */
    protected function _imageExists($image)
    {
        if (is_array($image)) {
            $image = $image[0];
        }

        $type = strtolower($this->getColumn()->getHeader()) === 'image' ? 'media' : 'album';

        $imagePath = sprintf(
            '%s/gallery/%s/%s',
            Mage::getBaseDir('media'),
            $type,
            $image
        );

        $fileIo = new Varien_Io_File();
        return $fileIo->fileExists($imagePath, true);
    }
}
