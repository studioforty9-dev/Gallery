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
 * Studioforty9_Gallery_Test_Block_Album_List
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Test
 */
class Studioforty9_Gallery_Test_Block_Album_List extends EcomDev_PHPUnit_Test_Case
{
    /** @var Studioforty9_Gallery_Block_Album_List */
    protected $block;

    public function setUp()
    {
        $this->block = new Studioforty9_Gallery_Block_Album_List();
    }

    public function test_getAlbumsCollection_only_includes_enabled_albums()
    {
        $collection = $this->block->getAlbumsCollection();

        /** @var Studioforty9_Gallery_Model_Album $album */
        foreach ($collection as $album) {
            $this->assertEquals(
                Studioforty9_Gallery_Model_Album::ENABLED,
                $album->getStatus()
            );
        }
    }

    public function test_getAlbumsCollection_is_ordered_by_position()
    {
        $collection = $this->block->getAlbumsCollection();

        $lastPosition = 0;

        /** @var Studioforty9_Gallery_Model_Album $album */
        foreach ($collection as $album) {
            $this->assertTrue($album->getPosition() > $lastPosition);
            $lastPosition = $album->getPosition();
        }
    }

    public function test_block_has_albums_collection_after_html()
    {
        $this->markTestSkipped();
        $this->block->setTemplate('studioforty9/gallery/album/view.phtml')->toHtml();
        $this->assertTrue($this->block->hasData('albums'));
    }
}
