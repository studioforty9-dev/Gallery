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
 * Studioforty9_Gallery_Model_Source_Album_Multiselect
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Model
 */
class Studioforty9_Gallery_Model_Source_Album_Multiselect
{
    /**
     * Create a list of multiselect options for selecting albums.
     *
     * @return array
     */
    public function toOptionsArray()
    {
        $albums = Mage::getModel('studioforty9_gallery/album')->getCollection()
            ->addFieldToSelect(array('name', 'entity_id'))
            ->addOrder('name', 'ASC');

        $options = array();
        if ($albums->count() > 0) {
            foreach ($albums as $album) {
                $options[] = array(
                    'label' => $album->getName(),
                    'value' => (int) $album->getId()
                );
            }
        }

        return $options;
    }
}
