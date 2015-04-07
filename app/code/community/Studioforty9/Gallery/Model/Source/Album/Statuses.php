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
 * Studioforty9_Gallery_Model_Source_Album_Statuses
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Model
 */
class Studioforty9_Gallery_Model_Source_Album_Statuses
{
    /**
     * The dropdown for statuses
     * 
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array(
                'value' => Studioforty9_Gallery_Model_Album::ENABLED,
                'label' => Mage::helper('studioforty9_gallery')->__('Enabled')
            ),
            array(
                'value' => Studioforty9_Gallery_Model_Album::DISABLED,
                'label' => Mage::helper('studioforty9_gallery')->__('Disabled')
            ),
        );
    }
}
