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
class Studioforty9_Gallery_Test_Model_Relations extends EcomDev_PHPUnit_Test_Case
{
    /** @var Studioforty9_Gallery_Model_Relations */
    protected $model;

    public function setUp()
    {
        $this->model = new Studioforty9_Gallery_Model_Relations();
    }

    /** @test */
    public function it_can_format_an_sql_update()
    {
        $this->model->init('album_id', '1', 'media_id', array('position'));
        $update = $this->model->formatUpdate(5, array('position' => 1));
        $this->assertEquals(
            "UPDATE studioforty9_gallery_media_album SET position=1 WHERE `media_id`=5 AND `album_id`=1",
            $update
        );
    }

    /** @test */
    public function it_can_format_an_sql_insert()
    {
        $this->model->init('album_id', '1', 'media_id', array('position'));
        $update = $this->model->formatInsert(5, array('position' => 1));
        $this->assertEquals(
            "INSERT INTO studioforty9_gallery_media_album SET position=1, `media_id`=5, `album_id`=1",
            $update
        );
    }

    /** @test */
    public function it_can_format_an_sql_delete()
    {
        $this->model->init('album_id', '1', 'media_id', array('position'));
        $update = $this->model->formatDelete(5, array('position' => 1));
        $this->assertEquals(
            "DELETE FROM studioforty9_gallery_media_album WHERE `media_id`=5 AND `album_id`=1",
            $update
        );
    }

    /** @test */
    public function it_can_retrieve_existing_relations()
    {
        $this->model->init('album_id', 1, 'media_id', array('position'));
        $existing = $this->model->getExistingRelations();

        foreach ($existing as $row) {
            $this->assertArrayHasKey('media_id', $row);
            $this->assertArrayHasKey('album_id', $row);
            $this->assertArrayHasKey('position', $row);
        }
    }

    /** @test */
    public function it_can_find_older_data_for_query_formatting()
    {
        $this->model->init('album_id', 1, 'media_id', array('position'));
        $existing = array(
            array('media_id' => 1, 'album_id' => 1, 'position' => 1),
            array('media_id' => 2, 'album_id' => 1, 'position' => 2),
        );
        $old = $this->model->formatOld($existing);

        foreach ($old as $id => $fields) {
            $this->assertArrayHasKey('position', $fields);
        }
    }

    /** @test */
    public function it_can_find_newer_data_for_query_formatting()
    {
        $entityIds = array(
            1 => array('position' => 2),
            2 => array('position' => 1)
        );
        $existing = array(
            array('media_id' => 1, 'album_id' => 1, 'position' => 1),
            array('media_id' => 2, 'album_id' => 1, 'position' => 2),
        );

        $this->model->init('album_id', 1, 'media_id', array('position'));

        $old = $this->model->formatOld($existing);
        $new = $this->model->formatNew($old, $entityIds);

        $this->assertArrayHasKey(1, $old);
        $this->assertArrayHasKey(1, $new);
        $this->assertArrayHasKey(1, $old);
        $this->assertArrayHasKey(2, $new);

        $this->assertArrayHasKey('position', $new[1]);
        $this->assertArrayHasKey('position', $new[2]);

        $this->assertEquals(2, $new[1]['position']);
        $this->assertEquals(1, $new[2]['position']);
    }
}
