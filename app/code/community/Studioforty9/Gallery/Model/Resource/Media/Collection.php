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
 * Studioforty9_Gallery_Model_Resource_Media_Collection
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Model
 */
class Studioforty9_Gallery_Model_Resource_Media_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Initialize the collection model.
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('studioforty9_gallery/media');
    }
}
