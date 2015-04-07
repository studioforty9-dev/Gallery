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
 * Studioforty9_Gallery_Block_Adminhtml_Album_Edit_Form
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Block
 */
class Studioforty9_Gallery_Block_Adminhtml_Album_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id'      => 'edit_form',
            'action'  => $this->getUrl('*/*/edit', array(
                'id' => $this->getRequest()->getParam('id')
            )),
            'method'  => 'post',
            'enctype' => 'multipart/form-data'
        ));

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
