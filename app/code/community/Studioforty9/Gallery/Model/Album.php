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
 * Studioforty9_Gallery_Model_Album
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Model
 */
class Studioforty9_Gallery_Model_Album extends Mage_Core_Model_Abstract
{
    /**
     * Entity code - can be used as part of method name for entity processing.
     * @const string
     */
    const ENTITY    = 'studioforty9_gallery_album';

    /**
     * The config mapping to the studioforty9_gallery_media_album table.
     * @const string
     */
    const PIVOT_TABLE = 'studioforty9_gallery/gallery_media_album';

    /**
     * TODO: figure out how this works with the caching system
     * @const string
     */
    const CACHE_TAG = 'studioforty9_gallery_album';

    /**
     * @const string
     */
    const ENABLED = 1;

    /**
     * @const string
     */
    const DISABLED = 0;

    /**
     * Prefix of model events names
     * @var string
     */
    protected $_eventPrefix = 'studioforty9_gallery_album';

    /**
     * Parameter name in event
     * @var string
     */
    protected $_eventObject = 'album';

    /**
     * The constructor will set the resource model
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('studioforty9_gallery/album');
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
     * @param int $width
     * @param int $height
     * @return string
     */
    public function getResizedImage($width, $height)
    {
        $source = Mage::helper('studioforty9_gallery')->getImagePath('album') . $this->getThumbnail();
        $image = Mage::helper('studioforty9_gallery/image')
            ->init($source, 'album')
            ->resize($width, $height);

        return $image->__toString();
    }

    /**
     * Get the URL of the album.
     *
     * @return string
     */
    public function getUrl()
    {
        return Mage::getUrl('gallery/album/'.$this->getUrlKey());
    }

    /**
     * @return array
     */
    public function getSelectedMediaIds()
    {
        return $this->getRelatedMedia()->getAllIds();
    }

    public function getRelatedMedia()
    {
        $entityId = $this->getId();
        /** @var Studioforty9_Gallery_Model_Resource_Media_Collection $collection */
        $collection = Mage::getModel('studioforty9_gallery/media')->getCollection();
        $collection->join(
            array('pivot' => 'studioforty9_gallery/gallery_media_album'),
            'pivot.media_id=main_table.entity_id AND pivot.album_id=' . (!empty($entityId) ? $entityId : 0),
            'position'
        );
        $collection->addOrder('pivot.position', 'ASC');

        return $collection;
    }

    /**
     * Sync the pivot table album/media ids and update the positions passed in.
     *
     * @param array $mediaIds
     * @return bool
     */
    public function syncMedia($mediaIds)
    {
        $relations = Mage::getModel('studioforty9_gallery/relations');
        $relations->init('album_id', $this->getId(), 'media_id', array('position'));
        $relations->sync($mediaIds);
    }
}
