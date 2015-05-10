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
 * Studioforty9_Gallery_Helper_Image
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Helper
 */
class Studioforty9_Gallery_Helper_Image extends Mage_Core_Helper_Abstract
{
    /** @var string $_name */
    protected $_name;

    /** @var string $_filePath */
    protected $_filePath;

    /** @var string $_fileName */
    protected $_fileName;

    /** @var string $_type */
    protected $_type = '';

    /** @var int $_width */
    protected $_width;

    /** @var int $_height */
    protected $_height;

    /** @var bool $_scheduleResize */
    protected $_scheduleResize = false;

    /** @var int $_quality */
    protected $_quality = 90;

    /** @var bool $_keepAspectRatio */
    protected $_keepAspectRatio = true;

    /** @var bool $_keepFrame */
    protected $_keepFrame = true;

    /** @var bool $_keepTransparency */
    protected $_keepTransparency = true;

    /** @var bool $_constrainOnly */
    protected $_constrainOnly = false;

    /** @var array $_backgroundColor */
    protected $_backgroundColor = array(255, 255, 255);

    /** @var array $_crop */
    protected $_crop = array(
        'top'    => 0,
        'left'   => 0,
        'right'  => 0,
        'bottom' => 0
    );

    /**
     * init()
     *
     * @param string $file
     * @param string $type
     * @return Studioforty9_Gallery_Helper_Image
     */
    public function init($file, $type = '')
    {
        $this->setFilePath($file);
        $this->setFileName(basename($file));
        $this->setType($type);
        return $this;
    }

    /**
     * Set the name of the image.
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    /**
     * Get the name of the image.
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * setFilePath()
     *
     * @param string $file
     * @return Studioforty9_Gallery_Helper_Image
     */
    public function setFilePath($file)
    {
        $this->_filePath = $file;
        return $this;
    }

    /**
     * getFilePath()
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->_filePath;
    }

    /**
     * setFileName()
     *
     * @param string $name
     * @return $this
     */
    public function setFileName($name)
    {
        $this->_fileName = $name;
        return $this;
    }

    /**
     * getFileName()
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->_fileName;
    }

    /**
     * setType()
     *
     * @param string $type
     * @return Studioforty9_Gallery_Helper_Image
     */
    public function setType($type)
    {
        $this->_type = $type;
        return $this;
    }

    /**
     * getType()
     *
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * setWidth()
     *
     * @param int $width
     * @return Studioforty9_Gallery_Helper_Image
     */
    public function setWidth($width)
    {
        $this->_width = $width;
        return $this;
    }

    /**
     * getWidth()
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->_width;
    }

    /**
     * setHeight()
     *
     * @param $height
     * @return Studioforty9_Gallery_Helper_Image
     */
    public function setHeight($height)
    {
        $this->_height = $height;
        return $this;
    }

    /**
     * getHeight()
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->_height;
    }

    /**
     * setQuality()
     *
     * Set image quality, values in percentage from 0 to 100
     *
     * @param int $quality
     * @return Mage_Catalog_Model_Product_Image
     */
    public function setQuality($quality)
    {
        $this->_quality = $quality;
        return $this;
    }

    /**
     * getQuality()
     *
     * Get image quality
     *
     * @return int
     */
    public function getQuality()
    {
        return $this->_quality;
    }

    /**
     * resize()
     *
     * @param int $width
     * @param int|null $height
     * @return $this
     */
    public function resize($width, $height = null)
    {
        $this->setWidth((int) $width)->setHeight((int) $height);
        $this->_scheduleResize = true;
        return $this;
    }

    /**
     * setConstrainOnly()
     *
     * @param boolean $constrainOnly
     * @return Studioforty9_Gallery_Helper_Image
     */
    public function setConstrainOnly($constrainOnly)
    {
        $this->_constrainOnly = $constrainOnly;
        return $this;
    }

    /**
     * getConstrainOnly()
     *
     * @return boolean
     */
    public function getConstrainOnly()
    {
        return $this->_constrainOnly;
    }

    /**
     * setKeepFrame()
     *
     * @param boolean $keepFrame
     * @return Studioforty9_Gallery_Helper_Image
     */
    public function setKeepFrame($keepFrame)
    {
        $this->_keepFrame = $keepFrame;
        return $this;
    }

    /**
     * getKeepFrame()
     *
     * @return boolean
     */
    public function getKeepFrame()
    {
        return $this->_keepFrame;
    }

    /**
     * setKeepAspectRatio()
     *
     * @param boolean $keepAspectRatio
     * @return Studioforty9_Gallery_Helper_Image
     */
    public function setKeepAspectRatio($keepAspectRatio)
    {
        $this->_keepAspectRatio = $keepAspectRatio;
        return $this;
    }

    /**
     * getKeepAspectRatio()
     *
     * @return boolean
     */
    public function getKeepAspectRatio()
    {
        return $this->_keepAspectRatio;
    }

    /**
     * setKeepTransparency()
     *
     * @param boolean $keepTransparency
     * @return Studioforty9_Gallery_Helper_Image
     */
    public function setKeepTransparency($keepTransparency)
    {
        $this->_keepTransparency = $keepTransparency;
        return $this;
    }

    /**
     * getKeepTransparency()
     *
     * @return boolean
     */
    public function getKeepTransparency()
    {
        return $this->_keepTransparency;
    }

    /**
     * @return array
     */
    public function getBackgroundColor()
    {
        return $this->_backgroundColor;
    }

    /**
     * Set the background color of the image.
     *
     * @param array $backgroundColor
     * @return $this
     */
    public function setBackgroundColor($backgroundColor)
    {
        if (is_string($backgroundColor)) {
            $backgroundColor = $this->hex2rgb($backgroundColor);
        }

        $this->_backgroundColor = $backgroundColor;

        return $this;
    }

    /**
     * Set the crop dimensions.
     *
     * @param int $top
     * @param int $left
     * @param int $right
     * @param int $bottom
     * @return $this
     */
    public function setCrop($top = 0, $left = 0, $right = 0, $bottom = 0)
    {
        $this->_crop = array(
            'top'    => $top,
            'left'   => $left,
            'right'  => $right,
            'bottom' => $bottom,
        );

        return $this;
    }

    /**
     * Get the crop dimensions.
     *
     * @return array
     */
    public function getCrop()
    {
        return $this->_crop;
    }

    /**
     * __toString()
     *
     * @return string
     */
    public function __toString()
    {
        // Check if $_filePath exists
        $filePath = $this->getFilePath();
        $fileName = $this->getFileName();

        $placeholder = $this->getPlaceholder();

        $ioFile = new Varien_Io_File();
        if (! $ioFile->fileExists($filePath)) {
            return $placeholder;
        }

        $cachePath = $this->getCachedFilePath();
        $cacheFile = $cachePath . DS .  $fileName;
        $cacheUrl  = $this->getImageUrl($cacheFile);

        if ($ioFile->fileExists($cacheFile)) {
            return $cacheUrl;
        }

        $_image = new Varien_Image($this->getFilePath());

        $mime = $_image->getMimeType();

        if ($this->getKeepAspectRatio()) {
            $_image->keepAspectRatio($this->getKeepAspectRatio());
        }

        if ($this->getKeepFrame()) {
            $_image->keepFrame($this->_keepFrame);
        }

        if ($this->getKeepTransparency()) {
            $_image->keepTransparency($this->_keepTransparency);
        }

        if ($this->getConstrainOnly()) {
            $_image->constrainOnly($this->_constrainOnly);
        }

        if ($this->getQuality()) {
            $_image->quality($this->_quality);
        }

        $_image->backgroundColor($this->_backgroundColor);
        //$_image->setImageBackgroundColor($this->rgb2hex($this->_backgroundColor));

        if ($this->_scheduleResize) {
            $_image->resize($this->getWidth(), $this->getHeight());
        } else {
            $_image->crop(
                $this->_crop['top'], $this->_crop['left'],
                $this->_crop['right'], $this->_crop['bottom']
            );
        }

        try {
            $_image->save($cacheFile, $this->getName());
        } catch (Exception $e) {
            Mage::logException($e);
            $cacheUrl = $placeholder;
        }

        return $cacheUrl;
    }

    /**
     * getImageUrl()
     *
     * @param string $path
     * @return string
     */
    public function getImageUrl($path)
    {
        $baseDir = Mage::getBaseDir('media');
        $baseUrl = rtrim(Mage::getBaseUrl('media'), DS);
        return str_replace($baseDir, $baseUrl, $path);
    }

    /**
     * getSkinUrl()
     *
     * @param string $path
     * @return string
     */
    public function getSkinUrl($path)
    {
        $baseDir = Mage::getBaseDir();
        $baseUrl = rtrim(Mage::getBaseUrl(), DS);
        return str_replace($baseDir, $baseUrl, $path);
    }

    /**
     * getCachedFilePath()
     *
     * @return string
     */
    public function getCachedFilePath()
    {
        $file = $this->getFilePath();
        $filename = $this->getFileName();
        $size = array($this->getWidth(), $this->getHeight());
        $path = str_replace($filename, 'cache' . DS . join('x', $size), $file);

        return $path;
    }

    /**
     * getPlaceholder()
     *
     * @return string
     */
    protected function getPlaceholder()
    {
        return '';
    }

    /**
     * Convert a hexadecimal string to an rgb array
     * @param $hex
     * @return array
     */
    public function hex2rgb($hex)
    {
        $hex = str_replace('#', '', $hex);

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }

        return array($r, $g, $b);
    }

    /**
     * Convert an rgb array to a hexadecimal string.
     *
     * @param $rgb
     * @return string
     */
    public function rgb2hex($rgb)
    {
        $hex = "#";
        $hex .= str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
        $hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
        $hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);

        return $hex;
    }
}
