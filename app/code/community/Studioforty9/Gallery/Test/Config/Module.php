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
 * Studioforty9_Gallery_Test_Config_Module
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Test
 */
class Studioforty9_Gallery_Test_Config_Module extends EcomDev_PHPUnit_Test_Case_Config
{
    public function test_module_is_in_correct_code_pool()
    {
        $this->assertModuleIsActive();
        $this->assertModuleCodePool('community');
    }

    public function test_module_version_is_correct()
    {
        $this->assertModuleVersion('1.0.0');
    }

    public function test_blocks_are_configured()
    {
        $this->assertBlockAlias(
            'studioforty9_gallery/album_list', 'Studioforty9_Gallery_Block_Album_List'
        );
    }

    public function test_models_are_configured()
    {
        $this->assertModelAlias('studioforty9_gallery/album', 'Studioforty9_Gallery_Model_Album');
        $this->assertModelAlias('studioforty9_gallery/media', 'Studioforty9_Gallery_Model_Media');
    }

    public function test_helpers_are_configured()
    {
        $this->assertHelperAlias('studioforty9_gallery/data', 'Studioforty9_Gallery_Helper_Data');
    }

    public function test_tables_are_configured()
    {
        $this->assertTableAlias('studioforty9_gallery/gallery_album', 'studioforty9_gallery_album');
        $this->assertTableAlias('studioforty9_gallery/gallery_album_store', 'studioforty9_gallery_album_store');
        $this->assertTableAlias('studioforty9_gallery/gallery_media', 'studioforty9_gallery_media');
        $this->assertTableAlias('studioforty9_gallery/gallery_media_store', 'studioforty9_gallery_media_store');
        $this->assertTableAlias('studioforty9_gallery/gallery_media_album', 'studioforty9_gallery_media_album');
    }

    /*public function test_access_granted_for_config_acl()
    {
        $this->assertConfigNodeValue(
            'config/adminhtml/acl/resources/admin/children/system/children/config/children/studioforty9/children/studioforty9_gallery/title',
            'StudioForty9 Gallery Configuration'
        );
    }*/

    public function test_layout_updates_are_correct()
    {
        $this->assertLayoutFileDefined('adminhtml', 'studioforty9_gallery.xml', 'studioforty9_gallery');
        $this->assertLayoutFileExists('adminhtml', 'studioforty9_gallery.xml', 'default', 'default');
        $this->assertLayoutFileDefined('frontend', 'studioforty9_gallery.xml', 'studioforty9_gallery');
        $this->assertLayoutFileExists('frontend', 'studioforty9_gallery.xml', 'default', 'base');
    }

    public function test_translate_nodes_are_correct()
    {
        $this->assertConfigNodeValue(
            'frontend/translate/modules/studioforty9_gallery/files/default',
            'Studioforty9_Gallery.csv'
        );
    }

    public function test_install_script_exists()
    {
        $this->assertSchemeSetupExists('Studioforty9_Gallery', 'studioforty9_gallery_setup');
        $this->assertSetupResourceDefined('Studioforty9_Gallery', 'studioforty9_gallery_setup');
        $this->assertSchemeSetupScriptVersions(
            '1.0.0',
            '1.0.0',
            'Studioforty9_Gallery',
            'studioforty9_gallery_setup'
        );
    }

    public function test_routes_are_configured()
    {
        $this->assertRouteFrontName('studioforty9_gallery', 'gallery', 'frontend');
    }
}
