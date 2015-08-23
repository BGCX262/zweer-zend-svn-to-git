<?php

class Admin_GalleryController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $Pages = new App_Model_DbTable_Pages();
        $this->view->pages = $Pages->getPagesWithGallery();
    }

    public function addAction()
    {
        $Gallery = new App_Model_DbTable_Gallery();
        $Pages = new App_Model_DbTable_Pages();

        $Form = new Admin_Form_Album();
        $Form->Submit->setLabel('Add');
        $Form->IDPage->addMultiOptions($this->view->printSelectPages($Pages->getTreePages()));
        $this->view->form = $Form;

        if($this->getRequest()->isPost())
        {
            $FormData = $this->getRequest()->getPost();

            if($Form->isValid($FormData))
            {
                $IDPage = $Form->getValue('IDPage');
                $Title = $Form->getValue('Title');
                $Description = $Form->getValue('Description');

                if($ID = $Gallery->addAlbum($Title, $Description, $IDPage))
                {
                    $Dir = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'gallery' . DIRECTORY_SEPARATOR;
                    if(!is_dir($Dir))
                        mkdir($Dir);

                    $Dir .= $ID . DIRECTORY_SEPARATOR;
                    if(!is_dir($Dir))
                        mkdir($Dir);
                    else
                    {
                        $Files = opendir($Dir);
                        while($File = readdir($Files))
                        {
                            if($File != '.' && $File != '..')
                                @unlink($Dir . $File);
                        }
                    }
                }

                $this->_helper->redirector('index');
            }
            else
            {
                $Form->populate($FormData);
            }
        }
    }

    public function editAction()
    {
        $Gallery = new App_Model_DbTable_Gallery();
        $Pages = new App_Model_DbTable_Pages();

        $Form = new Admin_Form_Album();
        $Form->Submit->setLabel('Edit');
        $Form->IDPage->addMultiOptions($this->view->printSelectPages($Pages->getTreePages()));
        $this->view->form = $Form;

        if($this->getRequest()->isPost())
        {
            $FormData = $this->getRequest()->getPost();

            if($Form->isValid($FormData))
            {
                $IDAlbum = $Form->getValue('IDAlbum');
                $IDPage = $Form->getValue('IDPage');
                $Title = $Form->getValue('Title');
                $Description = $Form->getValue('Description');

                $Gallery->updateAlbum($IDAlbum, $Title, $Description, $IDPage);

                $this->_helper->redirector('index');
            }
            else
            {
                $Form->populate($FormData);
            }
        }
        else
        {
            $IDAlbum = (int) $this->_getParam('IDAlbum', 0);
            if($IDAlbum)
            {
                $Form->populate($Gallery->getAlbum($IDAlbum));
            }
        }
    }

    public function deleteAction()
    {
        $IDAlbum = (int) $this->_getParam('IDAlbum', 0);
        if($IDAlbum)
        {
            $Gallery = new App_Model_DbTable_Gallery();
            $Gallery->deleteAlbum($IDAlbum);

            $this->_helper->redirector('index');
        }
    }

    public function uploadAction()
    {
        $Gallery = new App_Model_DbTable_Gallery();

        $this->view->IDAlbum = (int) $this->_getParam('IDAlbum', 0);

        if($this->getRequest()->isPost())
        {
            $Post = $this->getRequest()->getPost();
            $Files = $_FILES['img'];

            $Dir = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'gallery' . DIRECTORY_SEPARATOR . $this->view->IDAlbum . DIRECTORY_SEPARATOR;

            if($Files)
            {
                $ToPrint = array();

                for($I = 0; $I < count($Files['name']); ++$I)
                {
                    if('image/' != substr($Files['type'][$I], 0, 6))
                    {
                        $ToPrint[] = '"' . $Files['name'][$I] . '" is not a valid image file';
                        continue;
                    }
                    else
                    {
                        $Extension = strtolower(substr($Files['name'][$I], strrpos($Files['name'][$I], '.') + 1));
                        $Title = substr($Files['name'][$I], 0, strrpos($Files['name'][$I], '.'));

                        $IDPhoto = $Gallery->addPhoto($this->view->IDAlbum, $Extension, $Title);

                        @move_uploaded_file($Files['tmp_name'][$I], $Dir . $IDPhoto . '.' . $Extension);
                        $Img = new Zwe_Controller_Image($Dir . $IDPhoto . '.' . $Extension);
                        $Ratio = $Img->getRatio();
                        $Img->resizeTo(800, 600)->save($Dir . $IDPhoto . '_big.' . $Extension)
                            ->resizeTo(170, 170)->save($Dir . $IDPhoto . '_mean.' . $Extension)
                            ->resizeTo(90, 90)->save($Dir . $IDPhoto . '_small.' . $Extension);

                        $Gallery->updatePhoto($IDPhoto, false, false, false, false, $Ratio);

                        $ToPrint[] = '"' . $Files['name'][$I] . '" uploaded successfully';
                    }
                }

                echo implode("\n", $ToPrint);

                exit();
            }

            if($Post)
            {
                if($Post['i'] == 'Delete')
                {
                    $Gallery->deletePhotos($Post['delete']);

                    foreach($Post['delete'] as $IDDelete)
                    {
                        $Path = $Dir . $IDDelete . '*';
                        $Files = glob($Path);
                        @array_map('unlink', $Files);
                    }
                }
                elseif($Post['i'] == 'Modify')
                {

                }
            }
        }

        $this->view->photos = $Gallery->getPhotos($this->view->IDAlbum);
    }
}
