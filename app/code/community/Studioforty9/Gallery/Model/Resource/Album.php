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
 * Studioforty9_Gallery_Model_Resource_Album
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Model
 */
class Studioforty9_Gallery_Model_Resource_Album extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize the resource model.
     */
    public function _construct()
    {
        $this->_init('studioforty9_gallery/gallery_album', 'entity_id');
    }
}
