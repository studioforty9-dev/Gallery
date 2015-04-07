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
 * Studioforty9_Gallery_Block_Adminhtml_Album_Edit_Tab_Form
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Block
 */
class Studioforty9_Gallery_Block_Adminhtml_Album_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
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

        $fieldset = $form->addFieldset('album_form', array(
            'legend' => $this->_getHelper()->__('Album Content')
        ));

        $fieldset->addType('image', 'Studioforty9_Gallery_Block_Adminhtml_Media_Helper_Image');

        $this->_addFieldsToFieldset($fieldset, array(
            'name'        => $this->_getNameField(),
            'url_key'     => $this->_getUrlKeyField(),
            'thumbnail'   => $this->_getThumbnailField(),
            'description' => $this->_getDescriptionField(),
            'position'    => $this->_getPositionField(),
            'status'      => $this->_getStatusField()
        ));

        return parent::_prepareForm();
    }

    /**
     * Retrieve the existing banner for pre-populating the form fields.
     * For a new slide entry this will return an empty Media object.
     *
     * @return Studioforty9_Gallery_Model_Media
     */
    protected function _getAlbum()
    {
        if (! $this->hasData('album')) {
            $album = Mage::registry('current_album');
            if (!$album instanceof Studioforty9_Gallery_Model_Album) {
                $album = Mage::getModel('studioforty9_gallery/album');
            }
            $this->setData('album', $album);
        }

        return $this->getData('album');
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
            $existingValue = $this->_getAlbum()->getData($name);
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
     * @param string $helper
     * @return Studioforty9_Gallery_Helper_Data
     */
    protected function _getHelper($helper = '')
    {
        if (!empty($helper)) {
            return Mage::helper(sprintf('studioforty9_gallery/%s', $helper));
        }
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
     * _getNameField()
     *
     * @return array
     */
    protected function _getNameField()
    {
        return array(
            'input'    => 'text',
            'label'    => $this->_getHelper()->__('Name'),
            'class'    => 'required-entry',
            'required' => true
        );
    }

    /**
     * _getUrlKeyField()
     *
     * @return array
     */
    protected function _getUrlKeyField()
    {
        return array(
            'input'    => 'text',
            'label'    => $this->_getHelper()->__('URL Key'),
            //'class'    => 'required-entry',
            //'required' => true,
            'note'     => 'Leave blank to use the album name<br/>(e.g. My test album => my-test-album)'
        );
    }

    /**
     * _getThumbnailField()
     *
     * @return array
     */
    protected function _getThumbnailField()
    {
        return array(
            'input'  => 'image',
            'name'   => 'thumbnail',
            'label'  => $this->_getHelper()->__('Thumbnail'),
            'title'  => $this->_getHelper()->__('Thumbnail'),
            'class'    => 'required-entry',
            'required' => true,
        );
    }

    /**
     * _getDescriptionField()
     *
     * @return array
     */
    protected function _getDescriptionField()
    {
        return array(
            'input'    => 'editor',
            'name'     => 'description',
            'label'    => $this->_getHelper()->__('Description'),
            'title'    => $this->_getHelper()->__('Description'),
            'style'    => 'width:274px; height:200px;',
            'wysiwyg'  => false,
            'required' => false
        );
    }

    /**
     * _getPositionField()
     *
     * @return array
     */
    protected function _getPositionField()
    {
        return array(
            'input'     => 'text',
            'label'     => $this->_getHelper()->__('Position'),
            'class'     => 'required-entry',
            'required'  => true,
            'style'     => 'width: 60px;',
            'name'      => 'position'
        );
    }

    /**
     * _getStatusField()
     *
     * @return array
     */
    protected function _getStatusField()
    {
        return array(
            'input'    => 'select',
            'label'    => $this->_getHelper()->__('Status'),
            'class'    => 'required_entry',
            'values'   => $this->_getHelper('source')->getAlbumStatusOptions(),
            'required' => true,
            'name'     => 'status'
        );
    }
}
