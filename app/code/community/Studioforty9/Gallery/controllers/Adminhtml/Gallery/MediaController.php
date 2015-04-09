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
 * Studioforty9_Gallery_Adminhtml_Gallery_MediaController
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Controller
 * @layoutXml app/design/adminhtml/default/default/layout/studioforty9_gallery.xml
 */
class Studioforty9_Gallery_Adminhtml_Gallery_MediaController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Set some defaults on preDispatch
     */
    public function preDispatch()
    {
        parent::preDispatch();
        $this->_title($this->_getHelper()->__('Gallery'))
            ->_title($this->_getHelper()->__('Media'));
    }

    /**
     * The indexAction should display a grid.
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * The gridAction is actually more for ajax requests from the index grid.
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * The editAction displays the edit form.
     */
    public function editAction()
    {
        /* @var Studioforty9_Gallery_Model_Media $media */
        $media = Mage::getModel('studioforty9_gallery/media');

        // Guard against the media Id not existing
        if ($mediaId = $this->getRequest()->getParam('id', false)) {
            $media->load($mediaId);
            if ($media->getId() == 0) {
                $this->_getSession()->addError(
                    $this->__('The media content you referenced no longer exists.')
                );
                return $this->_redirect('*/*/');
            }
        }

        // process $_POST data if the form was submitted
        if ($this->getRequest()->isPost()) {
            $this->_saveAction($media, $this->getRequest()->getPost());
        }

        // make the current slide object available to blocks
        Mage::register('current_media', $media);

        // add the form container and tags
        $this->loadLayout();

        $this->_setActiveMenu('studioforty9/gallery');
        $this->_addBreadcrumb($this->__('Gallery'), $this->__('Media'));
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);

        $this->renderLayout();
    }

    /**
     * The _saveAction saves the data from the editAction.
     */
    protected function _saveAction($media, $postData)
    {
        $albumIds = array();
        if (array_key_exists('album_ids', $postData)) {
            foreach ($postData['album_ids'] as $albumId) {
                $albumIds[$albumId] = array();
            }
        }

        $deleteFile = false;
        $missingFile = false;
        if (array_key_exists('file', $postData)) {
            $file = $postData['file'];
            if (array_key_exists('delete', $file) && (int) $file['delete'] == 1) {
                $deleteFile = true;
            }
        }

        // Unset the image upload fields
        unset($postData['file'], $postData['form_key'], $postData['album_ids']);

        $media->addData($postData);

        if ($deleteFile) {
            $media->setData('file', '');
        }

        // Check if we have some image files to upload
        if (!empty($_FILES) && !empty($_FILES['file']['name'])) {
            try {
                $this->_uploadImages($media, array('file'));
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
            }
        } else {
            $currentFile = $media->getFile();
            if (!$deleteFile && empty($currentFile)) {
                $this->_getSession()->addError('The media file is required.');
                $missingFile = true;
            }
        }

        try {
            $media->save();
            $media->syncAlbums($albumIds);
            $this->_getSession()->addSuccess(
                $this->__('The media content was saved successfully.')
            );
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($e->getMessage());
            return $this->_redirect('*/*');
        }

        $route = ($deleteFile || $missingFile) ? '*/*/edit' : '*/*/index';

        return $this->_redirect($route, array('id' => $media->getId()));
    }

    /**
     * The sortAction is run via AJAX and saves the position order.
     */
    public function sortAction()
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            return false;
        }

        $params = array_filter($this->getRequest()->getParams(), function($param) {
            return is_numeric($param);
        });

        $media = Mage::getModel('studioforty9_gallery/media')->getCollection()
            ->addFieldToFilter('entity_id', array('in' => array_keys($params)));

        $transaction = Mage::getModel('core/resource_transaction');

        try {
            foreach ($media as $medium) {
                $medium->setData('position', $params[$medium->getId()]);
                $transaction->addObject($medium);
            }

            $transaction->save();
            $message = 'Order updated';
            $error = false;
        }
        catch (Exception $e) {
            $message = $e->getMessage();
            $error = true;
        }

        echo json_encode(array('error' => $error, 'message' => $message));
    }

    /**
     * The massDeleteAction deletes lots of media at once.
     */
    public function massDeleteAction()
    {
        $mediaIds = $this->getRequest()->getParam('media');
        $media = Mage::getModel('studioforty9_gallery/media')
            ->getCollection()
            ->addFieldToFilter('entity_id', array('in' => $mediaIds));

        if (empty($media)) {
            $this->_getSession()->addError($this->_getHelper()->__('No media selected for deletion.'));
            return $this->_redirect('*/*');
        }

        $transaction = Mage::getModel('core/resource_transaction');

        try {
            foreach ($media as $medium) {
                $transaction->addObject($medium);
            }

            $transaction->delete();
        }
        catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_getSession()->addSuccess(
            $this->_getHelper()->__(
                '%s / %s media items were deleted.',
                $media->count(),
                count($mediaIds)
            )
        );
        return $this->_redirect('*/*');
    }

    /**
     * The massStatusAction updates the status of lots of media at once.
     */
    public function massStatusAction()
    {
        $status = ($this->getRequest()->getParam('status') == 1)
                ? Studioforty9_Gallery_Model_Media::ENABLED
                : Studioforty9_Gallery_Model_Media::DISABLED;
        $mediaIds = $this->getRequest()->getParam('media');
        $media = Mage::getModel('studioforty9_gallery/media')
            ->getCollection()
            ->addFieldToFilter('entity_id', array('in' => $mediaIds));

        if (empty($media)) {
            $this->_getSession()->addError($this->_getHelper()->__('No media selected for status update.'));
            return $this->_redirect('*/*');
        }

        $transaction = Mage::getModel('core/resource_transaction');

        try {
            foreach ($media as $medium) {
                $medium->setStatus($status);
                $transaction->addObject($medium);
            }

            $transaction->save();
        }
        catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_getSession()->addSuccess(
            $this->_getHelper()->__(
                '%s / %s media items were updated.',
                $media->count(),
                count($mediaIds)
            )
        );
        return $this->_redirect('*/*');
    }

    /**
     * The massRelationAction relates lots of media to an album at once.
     */
    public function massRelationAction()
    {
        $albumId = (int) $this->getRequest()->getParam('album');
        $album = Mage::getModel('studioforty9_gallery/album')->load($albumId);

        if ($album->getId() == 0) {
            return $this->_redirect('*/*');
        }

        $mediaIds = array();
        $mediaIdsRaw = $this->getRequest()->getParam('media');
        if (!empty($mediaIdsRaw) && is_array($mediaIdsRaw)) {
            foreach ($mediaIdsRaw as $mediaId) {
                $mediaIds[$mediaId] = array();
            }
        }

        try {
            $album->syncMedia($mediaIds);
        }
        catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_getSession()->addSuccess(
            $this->_getHelper()->__(
                '%s media items were related to Album ID %s.',
                count($mediaIds),
                $albumId
            )
        );
        return $this->_redirect('*/*');
    }

    /* %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% */

    /**
     * Upload the images from the $_FILES array.
     *
     * @param Studioforty9_Gallery_Model_Media $media
     * @param array                            $uploadFields
     *
     * @throws Exception
     */
    protected function _uploadImages($media, $uploadFields)
    {
        foreach ($uploadFields as $image) {
            if (array_key_exists($image, $_FILES) && !empty($_FILES[$image]['name'])) {
                $uploader = new Varien_File_Uploader($image);
                // TODO: Make this a configuration option
                $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                // TODO: Make this a configuration option
                $uploader->setAllowRenameFiles(true);
                // TODO: Make this a configuration option
                $uploader->setFilesDispersion(false);
                // TODO: Make this a configuration option
                $path = $this->_getHelper()->getImagePath('media');
                $uploader->save($path, $media->getUrlKey() .'.'. $uploader->getFileExtension());
                $media->setData($image, $uploader->getUploadedFileName());
            }
        }
    }

    /**
     * @param null|string $name
     * @return Studioforty9_Gallery_Helper_Data
     */
    protected function _getHelper($name = null)
    {
        if (!is_null($name)) {
            return Mage::helper($name);
        }

        return Mage::helper('studioforty9_gallery');
    }
}