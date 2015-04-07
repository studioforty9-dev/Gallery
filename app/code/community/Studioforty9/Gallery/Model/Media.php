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
 * Studioforty9_Gallery_Model_Media
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Model
 */
class Studioforty9_Gallery_Model_Media extends Mage_Core_Model_Abstract
{
    /**
     * Entity code - can be used as part of method name for entity processing.
     * @const string
     */
    const ENTITY    = 'studioforty9_gallery_media';

    /**
     * The config mapping to the studioforty9_gallery_media_album table.
     * @const string
     */
    const PIVOT_TABLE = 'studioforty9_gallery/gallery_media_album';

    /**
     * TODO: figure out how this works with the caching system
     * @const string
     */
    const CACHE_TAG = 'studioforty9_gallery_media';

    /**
     * @const int
     */
    const ENABLED = 1;

    /**
     * @const int
     */
    const DISABLED = 0;

    /**
     * Prefix of model events names
     * @var string
     */
    protected $_eventPrefix = 'studioforty9_gallery_media';

    /**
     * Parameter name in event
     * @var string
     */
    protected $_eventObject = 'media';

    /**
     * constructor will set the resource model
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('studioforty9_gallery/media');
    }

    protected function _beforeSave()
    {
        parent::_beforeSave();

        // Auto save url key
        $helper = Mage::helper('studioforty9_gallery');
        $urlKey = $this->getUrlKey();
        if (empty($urlKey)) {
            $this->setUrlKey($helper->formatUrlKey($this->getName()));
        } else {
            $this->setUrlKey($helper->formatUrlKey($urlKey));
        }

        // Auto save dates
        $now = Varien_Date::now();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
            $this->setUpdatedAt($now);
        } else {
            $this->setUpdatedAt($now);
        }
    }

    /**
     * Fetch the full path to the resized image.
     *
     * @param int  $width
     * @param int  $height
     * @param int  $quality
     * @param bool $keepAspectRatio
     * @param bool $keepFrame
     * @return string
     */
    public function getResizedImage($width, $height, $quality = 80, $keepAspectRatio = true, $keepFrame = true)
    {
        $source = Mage::helper('studioforty9_gallery')->getImagePath('media') . $this->getFile();
        $image = Mage::helper('studioforty9_gallery/image')
            ->init($source, 'media')
            ->setQuality($quality)
            ->setKeepAspectRatio($keepAspectRatio)
            ->setKeepFrame($keepFrame)
            ->resize($width, $height);

        return $image->__toString();
    }

    /**
     * Get the URL of the media.
     *
     * @return string
     */
    public function getUrl()
    {
        return sprintf('gallery/media/%s/', $this->getUrlKey());
        /* TODO: Add config option to fallback to simple urls
        $url = Mage::getUrl('gallery/index/media', array('url_key' => $this->getUrlKey()));

        return $url;*/
    }

    public function getFileUrl()
    {
        $dir = Mage::helper('studioforty9_gallery')->getImageUrl('media');
        return $dir . $this->getFile();
    }

    /**
     * TODO: Add a store filter
     * @return array
     */
    public function getSelectedAlbumIds()
    {
        return $this->getRelatedAlbums()->getAllIds();
    }

    /**
     * TODO: Add a store filter
     * @return mixed
     */
    public function getRelatedAlbums()
    {
        $entityId = $this->getId();
        /** @var Studioforty9_Gallery_Model_Resource_Media_Collection $collection */
        $collection = Mage::getModel('studioforty9_gallery/album')->getCollection();
        $collection->join(
            array('pivot' => 'studioforty9_gallery/gallery_media_album'),
            'pivot.album_id=main_table.entity_id AND pivot.media_id=' . (!empty($entityId) ? $entityId : 0),
            'position'
        );
        $collection->addOrder('main_table.position', 'ASC');

        return $collection;
    }

    /**
     * Sync the pivot table album/media ids and update the positions passed in.
     *
     * @param array $albumIds
     * @return bool
     */
    public function syncAlbums($albumIds)
    {
        $relations = Mage::getModel('studioforty9_gallery/relations');
        $relations->init('media_id', $this->getId(), 'album_id', array('position'));
        $relations->sync($albumIds);
    }
}
