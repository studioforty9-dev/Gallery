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
 * Studioforty9_Gallery_Adminhtml_Gallery_AlbumController
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Controller
 */
class Studioforty9_Gallery_Adminhtml_Gallery_AlbumController extends Mage_Adminhtml_Controller_Action
{
    public function preDispatch()
    {
        parent::preDispatch();
        $this->_title($this->_getHelper()->__('Gallery'))
             ->_title($this->_getHelper()->__('Albums'));
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function sortAction()
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            return false;
        }

        $params = array_filter($this->getRequest()->getParams(), function($param) {
            return is_numeric($param);
        });

        $albums = Mage::getModel('studioforty9_gallery/album')->getCollection()
            ->addFieldToFilter('entity_id', array('in' => array_keys($params)));

        $transaction = Mage::getModel('core/resource_transaction');

        try {
            foreach ($albums as $album) {
                $album->setData('position', $params[$album->getId()]);
                $transaction->addObject($album);
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

    public function newAction()
    {
        return $this->_forward('edit');
    }

    public function mediaTabAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function mediaGridAction()
    {
        $selectedMedia = $this->getRequest()->getPost('media_selected', null);

        $this->loadLayout();
        $this->getLayout()->getBlock('album.mediagrid')->setSelectedMedia($selectedMedia);
        $this->renderLayout();
    }

    public function editAction()
    {
        /* @var Studioforty9_Gallery_Model_Album $album */
        $album = Mage::getModel('studioforty9_gallery/album');

        // Guard against the album Id not existing
        if ($albumId = $this->getRequest()->getParam('id', false)) {
            $album->load($albumId);
            if ($album->getId() == 0) {
                $this->_getSession()->addError(
                    $this->__('The album you referenced no longer exists.')
                );
                return $this->_redirect('*/*/');
            }
        }

        // process $_POST data if the form was submitted
        if ($this->getRequest()->isPost()) {
            $this->_saveAction($album, $this->getRequest()->getPost());
        }

        // make the current album object available to blocks
        Mage::register('current_album', $album);

        // add the form container and tags
        $this->loadLayout();

        $this->_setActiveMenu('studioforty9/gallery');
        $this->_addBreadcrumb($this->__('Gallery'), $this->__('Album'));
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);

        $this->renderLayout();
    }

    protected function _saveAction(Studioforty9_Gallery_Model_Album $album, $postData)
    {
        if ($mediaIds = $this->getRequest()->getParam('media_ids', null)) {
            $mediaIds = Mage::helper('adminhtml/js')->decodeGridSerializedInput($postData['media_ids']);
        }

        $deleteThumbnail = false;
        $missingThumbnail = false;
        if (array_key_exists('thumbnail', $postData)) {
            $thumbnail = $postData['thumbnail'];
            if (array_key_exists('delete', $thumbnail) && (int) $thumbnail['delete'] == 1) {
                $deleteThumbnail = true;
            }
        }

        // Unset the useless fields
        $ignored = array(
            'page', 'limit', 'form_key', 'selected_media', 'media_ids', 'thumbnail',
            'grid_entity_id', 'grid_image', 'grid_name', 'grid_status', 'grid_position'
        );
        foreach ($ignored as $ignore) {
            unset($postData[$ignore]);
        }

        $album->addData($postData);

        if ($deleteThumbnail) {
            $album->setData('thumbnail', '');
        }

        // Check if we have some image files to upload
        if (!empty($_FILES) && !empty($_FILES['thumbnail']['name'])) {
            try {
                $this->_uploadImages($album, array('thumbnail'));
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
            }
        } else {
            if (!$deleteThumbnail) {
                $this->_getSession()->addError('The thumbnail file is required.');
                $missingThumbnail = true;
            }
        }

        try {
            $album->save();
            $album->syncMedia($mediaIds);
            $this->_getSession()->addSuccess(
                $this->__('The album content was saved successfully.')
            );
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($e->getMessage());
            return $this->_redirect('*/*');
        }

        $route = ($deleteThumbnail || $missingThumbnail) ? '*/*/edit' : '*/*/index';

        return $this->_redirect($route, array('id' => $album->getId()));
    }

    public function deleteAction()
    {
        $albumId = $this->getRequest()->getParam('id');
        $album = Mage::getModel('studioforty9_gallery/album')->load($albumId);

        try {
            $album->delete();
        }
        catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_getSession()->addSuccess(
            $this->_getHelper()->__('Album was deleted successfully.')
        );

        return $this->_redirect('*/*');
    }

    public function massDeleteAction()
    {
        $albumIds = $this->getRequest()->getParam('albums');
        $albums = Mage::getModel('studioforty9_gallery/album')
            ->getCollection()
            ->addFieldToFilter('entity_id', array('in' => $albumIds));

        if (empty($albums)) {
            $this->_getSession()->addError($this->_getHelper()->__('No albums selected for deletion.'));
            return $this->_redirect('*/*');
        }

        $transaction = Mage::getModel('core/resource_transaction');

        try {
            foreach ($albums as $album) {
                $transaction->addObject($album);
            }

            $transaction->delete();
        }
        catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_getSession()->addSuccess(
            $this->_getHelper()->__(
                '%s / %s albums were deleted.',
                $albums->count(),
                count($albumIds)
            )
        );
        return $this->_redirect('*/*');
    }

    public function massStatusAction()
    {
        $status = ($this->getRequest()->getParams('status') == 1)
                ? Studioforty9_Gallery_Model_Album::ENABLED
                : Studioforty9_Gallery_Model_Album::DISABLED;

        $albumIds = $this->getRequest()->getParam('albums');
        $albums = Mage::getModel('studioforty9_gallery/album')
            ->getCollection()
            ->addFieldToFilter('entity_id', array('in' => $albumIds));

        if (empty($albums)) {
            $this->_getSession()->addError($this->_getHelper()->__('No albums selected for status update.'));
            return $this->_redirect('*/*');
        }

        $transaction = Mage::getModel('core/resource_transaction');

        try {
            foreach ($albums as $album) {
                $album->setStatus($status);
                $transaction->addObject($album);
            }

            $transaction->save();
        }
        catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_getSession()->addSuccess(
            $this->_getHelper()->__(
                '%s / %s albums were updated.',
                $albums->count(),
                count($albumIds)
            )
        );
        return $this->_redirect('*/*');
    }

    /* %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% */

    /**
     * Upload the images from the $_FILES array.
     *
     * @param $model
     * @param $uploadFields
     * @throws Exception
     * @internal param Varien_Object $model
     */
    protected function _uploadImages($model, $uploadFields)
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
                $path = $this->_getHelper()->getImagePath('album');
                $uploader->save($path, $model->getUrlKey() .'.'. $uploader->getFileExtension());
                $model->setData($image, $uploader->getUploadedFileName());
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