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
 * Studioforty9_Gallery_Block_Album_List
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Block
 */
class Studioforty9_Gallery_Block_Album_List extends Mage_Core_Block_Template
{
    /**
     * Set the collection on the block before rendering the html.
     */
    public function _beforeToHtml()
    {
        $this->setData('albums', $this->getAlbumsCollection());
    }

    /**
     * Get the album collection with only enabled albums sorted in
     * ascending order by position.
     *
     * @return Studioforty9_Gallery_Model_Resource_Album_Collection
     */
    public function getAlbumsCollection()
    {
        $collection = Mage::getModel('studioforty9_gallery/album')->getCollection();
        $collection->addOrder('position', 'ASC')
            ->addFieldToFilter('status',
                array('eq' => Studioforty9_Gallery_Model_Album::ENABLED)
            );

        return $collection;
    }
}
