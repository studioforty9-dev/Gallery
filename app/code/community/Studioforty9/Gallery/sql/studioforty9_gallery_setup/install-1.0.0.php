<?php
/**
 * StudioForty9 Gallery
 *
 * @category    StudioForty9
 * @package     StudioForty9_Gallery
 * @copyright   Copyright (c) 2015 StudioForty9 (http://www.studioforty9.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('studioforty9_gallery/gallery_album'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Album ID')
    // ALBUM NAME
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Album Name')
    // DESCRIPTION
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
        'nullable'  => false,
    ), 'Album Description')
    // URL KEY
    ->addColumn('url_key', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Album URL key')
    // THUMBNAIL PATH
    ->addColumn('thumbnail', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Thumbnail')
    // META TITLE
    ->addColumn('meta_title', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Meta title')
    // META KEYWORDS
    ->addColumn('meta_keywords', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
    ), 'Meta keywords')
    // META DESCRIPTION
    ->addColumn('meta_description', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
    ), 'Meta description')
    // STATUS
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
    ), 'Enabled')
    // ORDER
    ->addColumn('position', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
    ), 'Order Position')
    // UPDATED AT
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
    ), 'Album Modification Time')
    // CREATED AT
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
    ), 'Album Creation Time')
    ->setComment('Gallery Album Table');
$installer->getConnection()->createTable($table);

$table = $installer->getConnection()
    ->newTable($installer->getTable('studioforty9_gallery/gallery_media'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Media ID')
    // FRIENDLY FILE NAME
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Media Name')
    // CAPTION
    ->addColumn('caption', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Caption')
    // DESCRIPTION
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
        'nullable'  => false,
    ), 'Description')
    // FILE PATH
    ->addColumn('file', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Filepath')
    // URL KEY
    ->addColumn('url_key', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'URL key')
    // META TITLE
    ->addColumn('meta_title', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Meta title')
    // META KEYWORDS
    ->addColumn('meta_keywords', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
    ), 'Meta keywords')
    // META DESCRIPTION
    ->addColumn('meta_description', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
    ), 'Meta description')
    // STATUS
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
    ), 'Enabled')
    // UPDATED AT
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
    ), 'Media Modification Time')
    // CREATED AT
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
    ), 'Media Creation Time')
    ->setComment('Gallery Media Table');
$installer->getConnection()->createTable($table);

/**
 * Media - Album Join Table
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('studioforty9_gallery/gallery_media_album'))
    ->addColumn('media_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => false,
    ), 'Media ID')
    ->addColumn('album_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => false,
    ), 'Album ID')
    ->addIndex(
        $installer->getIdxName('studioforty9_gallery/gallery_media', array('media_id')),
        array('media_id')
    )
    ->addIndex(
        $installer->getIdxName('studioforty9_gallery/gallery_album', array('album_id')),
        array('album_id')
    )
    // ORDER
    ->addColumn('position', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
    ), 'Order Position')
    ->addForeignKey(
        $installer->getFkName(
            'studioforty9_gallery/gallery_media_album',
            'media_id',
            'studioforty9_gallery/gallery_media',
            'entity_id'
        ),
        'media_id',
        $installer->getTable('studioforty9_gallery/gallery_media'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName(
            'studioforty9_gallery/gallery_media_album',
            'album_id',
            'studioforty9_gallery/gallery_album',
            'entity_id'
        ),
        'album_id',
        $installer->getTable('studioforty9_gallery/gallery_album'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Media To Album Linkage Table');
$installer->getConnection()->createTable($table);

/**
 * Album Store Table
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('studioforty9_gallery/gallery_album_store'))
    ->addColumn('album_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Album ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Store ID')
    ->addIndex(
        $installer->getIdxName('studioforty9_gallery/gallery_album_store', array('album_id')),
        array('store_id')
    )
    ->addForeignKey(
        $installer->getFkName(
            'studioforty9_gallery/gallery_album_store',
            'album_id',
            'studioforty9_gallery/gallery_album',
            'entity_id'
        ),
        'album_id',
        $installer->getTable('studioforty9_gallery/gallery_album'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName(
            'studioforty9_gallery/gallery_album_store',
            'store_id',
            'core/store',
            'store_id'
        ),
        'store_id',
        $installer->getTable('core/store'),
        'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Album To Store Linkage Table');
$installer->getConnection()->createTable($table);

/**
 * Media Store Table
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('studioforty9_gallery/gallery_media_store'))
    ->addColumn('media_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Media ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Store ID')
    ->addIndex(
        $installer->getIdxName('studioforty9_gallery/gallery_media_store', array('media_id')),
        array('store_id')
    )
    ->addForeignKey(
        $installer->getFkName(
            'studioforty9_gallery/gallery_media_store',
            'media_id',
            'studioforty9_gallery/gallery_media',
            'entity_id'
        ),
        'media_id',
        $installer->getTable('studioforty9_gallery/gallery_media'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName(
            'studioforty9_gallery/gallery_media_store',
            'store_id',
            'core/store',
            'store_id'
        ),
        'store_id',
        $installer->getTable('core/store'),
        'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Media To Store Linkage Table');
$installer->getConnection()->createTable($table);

$installer->endSetup();
