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
 * Studioforty9_Gallery_Block_Adminhtml_Media_Edit_Tab_Meta
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Block
 */
class Studioforty9_Gallery_Block_Adminhtml_Media_Edit_Tab_Meta extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * _prepareForm()
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset('media_form', array(
            'legend' => $this->_getHelper()->__('Meta Content')
        ));

        $this->_addFieldsToFieldset($fieldset, array(
            'meta_title'       => $this->_getMetaTitleField(),
            'meta_keywords'    => $this->_getMetaKeywordsField(),
            'meta_description' => $this->_getMetaDescriptionField(),
        ));

        return parent::_prepareForm();
    }

    /**
     * Retrieve the existing banner for pre-populating the form fields.
     * For a new slide entry this will return an empty Media object.
     *
     * @return Studioforty9_Gallery_Model_Media
     */
    protected function _getMedia()
    {
        if (! $this->hasData('media')) {
            $media = Mage::registry('current_media');
            if (!$media instanceof Studioforty9_Gallery_Model_Media) {
                $media = Mage::getModel('studioforty9_gallery/media');
            }
            $this->setData('media', $media);
        }

        return $this->getData('media');
    }

    /**
     * This method makes life a little easier for us by pre-populating
     * fields with $_POST data where applicable and wraps our post data in
     * 'slideData' so we can easily separate all relevant information in
     * the controller.
     *
     * @param Varien_Data_Form_Element_Fieldset $fieldset
     * @param array                             $fields
     * @return $this
     */
    protected function _addFieldsToFieldset(Varien_Data_Form_Element_Fieldset $fieldset, $fields)
    {
        $requestData = new Varien_Object($this->getRequest()->getPost());

        foreach ($fields as $name => $_data) {
            if ($requestValue = $requestData->getData($name)) {
                $_data['value'] = $requestValue;
            }
            // wrap all fields with slide group
            $_data['name'] = $name;
            // generally label and title always the same
            $_data['title'] = $_data['label'];
            // if no new value exists, use existing banner data
            $existingValue = $this->_getMedia()->getData($name);
            if (!array_key_exists('value', $_data) || array_key_exists('value', $_data) && $_data['value'] !== $existingValue) {
                $_data['value'] = $existingValue;
            }
            // finally call vanilla functionality to add field
            $fieldset->addField($name, $_data['input'], $_data);
        }

        return $this;
    }

    /**
     * _getHelper()
     *
     * @return Studioforty9_Gallery_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('studioforty9_gallery');
    }

    /**
     * _getSession()
     *
     * @return Mage_Adminhtml_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('adminhtml/session');
    }

    /**
     * _getMetaTitleField()
     *
     * @return array
     */
    protected function _getMetaTitleField()
    {
        return array(
            'input'    => 'text',
            'label'    => $this->_getHelper()->__('Meta Title'),
            'class'    => '',
            'required' => false,
            'name'     => 'name'
        );
    }

    /**
     * _getMetaKeywordsField()
     *
     * @return array
     */
    protected function _getMetaKeywordsField()
    {
        return array(
            'input'    => 'text',
            'label'    => $this->_getHelper()->__('Meta Keywords'),
            'class'    => '',
            'required' => false,
            'name'     => 'meta_keywords'
        );
    }

    /**
     * _getMetaDescriptionField()
     *
     * @return array
     */
    protected function _getMetaDescriptionField()
    {
        return array(
            'input'    => 'editor',
            'name'     => 'meta_description',
            'label'    => $this->_getHelper()->__('Meta Description'),
            'title'    => $this->_getHelper()->__('Meta Description'),
            'style'    => 'width:274px; height:200px;',
            'class'    => '',
            'wysiwyg'  => false,
            'required' => false
        );
    }
}
