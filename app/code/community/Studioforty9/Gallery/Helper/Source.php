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
 * Studioforty9_Gallery_Helper_Source
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Helper
 */
class Studioforty9_Gallery_Helper_Source extends Mage_Core_Helper_Abstract
{
    /**
     * Get the media statuses as dropdown options.
     *
     * @return array
     */
    public function getMediaStatusOptions()
    {
        $statuses = Mage::getModel('studioforty9_gallery/source_media_statuses');

        return $statuses->toOptionArray();
    }

    /**
     * Get the album statuses as dropdown options.
     *
     * @return array
     */
    public function getAlbumStatusOptions()
    {
        $statuses = Mage::getModel('studioforty9_gallery/source_album_statuses');

        return $statuses->toOptionArray();
    }

    public function getAlbumOptions()
    {
        $model = Mage::getModel('studioforty9_gallery/album')->getCollection();

        return $model->toOptionArray();
    }

    /**
     * Get the album names as dropdown options.
     *
     * @return array
     */
    public function getAlbumMultiselectOptions()
    {
        $source = Mage::getModel('studioforty9_gallery/source_album_multiselect');

        return $source->toOptionsArray();
    }
}
