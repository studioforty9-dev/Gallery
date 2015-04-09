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
 * Studioforty9_Gallery_IndexController
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Controller
 */
class Studioforty9_Gallery_IndexController extends Mage_Core_Controller_Front_Action
{
    /** @var Studioforty9_Gallery_Helper_Data */
    protected $_helper;

    /**
     * _construct runs before every action, consider preDispatch() here too.
     */
    protected function _construct()
    {
        $this->_helper = Mage::helper('studioforty9_gallery');
    }

    /**
     * The index action should be the starting point for viewing media in
     * the gallery. You can configure the extension to use albums as the
     * index page or a specific albums media.
     *
     * @return self
     */
    public function indexAction()
    {
        $this->_initGalleryLayout();
        $this->_initSeoContent();
        return $this->renderLayout();
    }

    /**
     * The album controller action is responsible for displaying a
     * specific album.
     *
     * @return Mage_Core_Controller_Varien_Action
     */
    public function albumAction()
    {
        $urlKey = $this->getRequest()->get('url_key');
        if (!$urlKey) {
            return $this->norouteAction();
        }

        $album = Mage::helper('studioforty9_gallery/album')->getAlbumByUrlKey($urlKey);
        Mage::getSingleton('core/session')->setAlbumUrlKey($urlKey);
        Mage::register('current_album', $album);

        $this->_initGalleryLayout();
        $this->_initSeoContent(
            '' === $album->getMetaTitle() ? $album->getMetaTitle() : $album->getName(),
            $album->getMetaKeywords(),
            $album->getMetaDescription()
        );

        if ($this->_helper->getUseBreadcrumbs()) {
            if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbs->addCrumb('album', array(
                    'label' => $this->_helper->__($album->getName())
                ));
            }
        }

        return $this->renderLayout();
    }

    public function mediaAction()
    {
        $urlKey = $this->getRequest()->get('url_key');
        if (! $urlKey) {
            return $this->norouteAction();
        }

        /** @var Studioforty9_Gallery_Helper_Album $albumHelper */
        $albumHelper = Mage::helper('studioforty9_gallery/album');
        $albumUrlKey = Mage::getSingleton('core/session')->getData('album_url_key', false);
        $album = $albumHelper->getAlbumByUrlKey($albumUrlKey);
        Mage::register('current_album', $album);

        /** @var Studioforty9_Gallery_Helper_Media $mediaHelper */
        $mediaHelper = Mage::helper('studioforty9_gallery/media');
        $media = $mediaHelper->getMediaByUrlKey($urlKey);
        Mage::register('current_media', $media);

        $this->_initGalleryLayout();
        $this->_initSeoContent(
            '' === $media->getMetaTitle() ? $media->getMetaTitle() : $media->getName(),,
            $media->getMetaKeywords(),
            $media->getMetaDescription()
        );

        if ($this->_helper->getUseBreadcrumbs()) {
            if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbs->addCrumb('album', array(
                    'label' => $this->_helper->__($album->getName()),
                    'link'  => $albumHelper->getAlbumUrl()
                ));
                $breadcrumbs->addCrumb('media', array(
                    'label' => $this->_helper->__($media->getName())
                ));
            }
        }

        return $this->renderLayout();
    }

    protected function _initGalleryLayout()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
    }

    protected function _initSeoContent($title = null, $keywords = null, $description = null)
    {
        $title = is_null($title) ? $this->_helper->getSeoTitle() : $title;
        $keywords = is_null($keywords) ? $this->_helper->getSeoKeywords() : $keywords;
        $description = is_null($description) ? $this->_helper->getSeoDescription() : $description;

        if ($headBlock = $this->getLayout()->getBlock('head')) {
            $headBlock->setTitle($title);
            $headBlock->setKeywords($keywords);
            $headBlock->setDescription($description);
        }

        return $headBlock;
    }
}
