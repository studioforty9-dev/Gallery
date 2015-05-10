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
 * Studioforty9_Gallery_Test_Model_Relations
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Test
 */
class Studioforty9_Gallery_Test_Helper_Image extends EcomDev_PHPUnit_Test_Case
{
    /** @var Studioforty9_Gallery_Helper_Image */
    protected $helper;

    protected $file = '/slide/image/Home-sets.jpg';

    public function setUp()
    {
        $this->helper = Mage::helper('studioforty9_gallery/image');
    }

    private function _getFile()
    {
        return Mage::getBaseDir('media') . $this->file;
    }

    public function test_it_can_crop_an_image_to_the_expected_dimensions()
    {
        $file = $this->_getFile();

        $this->helper->init($file);
        $this->helper->setWidth(372);
        $this->helper->setHeight(404);
        $this->helper->setCrop(0, 364, 364, 0);

        $create = $this->helper->__toString();

        $image = $this->helper->getCachedFilePath();

        //var_dump($image);

    }

    public function tearDown()
    {
        $this->helper = null;
    }
}
