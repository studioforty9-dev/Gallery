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
 * Studioforty9_Gallery_Block_Adminhtml_Media_Edit_Tab_Form
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Block
 */
class Studioforty9_Gallery_Block_Adminhtml_Media_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
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
            'legend' => $this->_getHelper()->__('Media Content')
        ));

        $fieldset->addType('image', 'Studioforty9_Gallery_Block_Adminhtml_Media_Helper_Image');

        $this->_addFieldsToFieldset($fieldset, array(
            'name'        => $this->_getNameField(),
            'url_key'     => $this->_getUrlKeyField(),
            'file'        => $this->_getFileField(),
            'caption'     => $this->_getCaptionField(),
            'description' => $this->_getDescriptionField(),
            'album_ids'   => $this->_getAlbumsField(),
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
            $requestValue = $requestData->getData($name);
            if (!empty($requestValue)) {
                $_data['value'] = $requestValue;
            }
            // wrap all fields with slide group
            $_data['name'] = $name;
            // generally label and title always the same
            $_data['title'] = $_data['label'];

            // if no new value exists, use existing banner data
            $existingValue = $this->_getMedia()->getData($name);
            if (!empty($existingValue)) {
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
            'required' => true,
            'name'     => 'name'
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
            'name'     => 'url_key',
            'input'    => 'text',
            'label'    => $this->_getHelper()->__('URL Key'),
            //'class'    => 'required-entry',
            //'required' => true,
            'note'     => $this->_getHelper()->__(
                'Leave blank to use the album name<br/>(e.g. My test album => my-test-album)'
            )
        );
    }

    /**
     * _getFileField()
     *
     * @return array
     */
    protected function _getFileField()
    {
        return array(
            'input'  => 'image',
            'name'   => 'file',
            'label'  => $this->_getHelper()->__('Media Image'),
            'title'  => $this->_getHelper()->__('Media Image'),
            'class'    => 'required-entry',
            'required' => true,
        );
    }

    /**
     * _getCaptionField()
     *
     * @return array
     */
    protected function _getCaptionField()
    {
        return array(
            'input'    => 'text',
            'label'    => $this->_getHelper()->__('Media Caption'),
            'class'    => '',
            'required' => false,
            'name'     => 'caption',
            'value'    => ''
        );
    }

    /**
     * _getSummaryField()
     *
     * @return array
     */
    protected function _getDescriptionField()
    {
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(
            array(
                'tab_id' => 'form_section',
                'add_widgets' => false,
                'add_variables' => false,
                'add_images' => false
            )
        );

        return array(
            'input'    => 'editor',
            'name'     => 'description',
            'label'    => $this->_getHelper()->__('Description'),
            'title'    => $this->_getHelper()->__('Description'),
            'required' => false,
            'class'    => '',
            'style'    => 'width:274px; height:500px;',
            'wysiwyg' => true,
            'state' => 'html',
            'config' => $wysiwygConfig
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
            'name'      => 'position',
            'style'     => 'width: 60px;'
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
            'input'  => 'select',
            'label'  => $this->_getHelper()->__('Status'),
            'name'   => 'status',
            'class'  => 'required_entry',
            'values' => $this->_getHelper('source')->getMediaStatusOptions(),
            'required' => true
        );
    }

    /**
     * _getAlbumsField()
     *
     * @return array
     */
    protected function _getAlbumsField()
    {
        return array(
            'name'     => 'album_ids',
            'help'     => 'The albums to attach to.',
            'input'    => 'multiselect',
            'label'    => $this->_getHelper()->__('Album'),
            //'class'    => 'required_entry',
            'required' => false,
            'values'   => $this->_getHelper('source')->getAlbumMultiselectOptions(),
            'value'    => $this->_getMedia()->getSelectedAlbumIds()
        );
    }
}
