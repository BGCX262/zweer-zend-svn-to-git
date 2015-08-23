<?php

class Admin_PagesController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $Pages = new App_Model_DbTable_Pages();
        $this->view->pages = $Pages->getTreePages();
    }

    public function addAction()
    {
        $Page = new App_Model_DbTable_Pages();

        $Form = new Admin_Form_Pages();
        $Form->Submit->setLabel('Add page');
        $Form->IDParent->addMultiOptions($this->view->printSelectPages($Page->getTreePages()));
        $this->view->form = $Form;

        if($this->getRequest()->isPost())
        {
            $FormData = $this->getRequest()->getPost();

            if($Form->isValid($FormData))
            {
                $Title = $Form->getValue('Title');
                $Text = $Form->getValue('Text');
                $URL = $Form->getValue('URL');
                $Type = $Form->getValue('Type');
                $IDParent = $Form->getValue('IDParent');

                $Page->addPage($Title, $URL, $Type, $Text, $IDParent);

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
        $Page = new App_Model_DbTable_Pages();

        $Form = new Admin_Form_Pages();
        $Form->Submit->setLabel('Edit page');
        $Form->IDParent->addMultiOptions($this->view->printSelectPages($Page->getTreePages()));
        $this->view->form = $Form;

        if($this->getRequest()->isPost())
        {
            $FormData = $this->getRequest()->getPost();

            if($Form->isValid($FormData))
            {
                $IDPage = (int) $Form->getValue('IDPage');
                $Title = $Form->getValue('Title');
                $Text = $Form->getValue('Text');
                $URL = $Form->getValue('URL');
                $Type = $Form->getValue('Type');
                $IDParent = $Form->getValue('IDParent');

                $Page->updatePage($IDPage, $Title, $URL, $Type, $Text, $IDParent);

                $this->_helper->redirector('index');
            }
            else
            {
                $Form->populate($FormData);
            }
        }
        else
        {
            $IDPage = (int) $this->_getParam('IDPage', 0);
            if($IDPage)
            {
                $Form->populate($Page->getPage($IDPage));
            }
        }
    }

    public function deleteAction()
    {
        $IDPage = (int) $this->_getParam('IDPage', 0);
        if($IDPage)
        {
            $Page = new App_Model_DbTable_Pages();
            $Page->deletePage($IDPage);

            $this->_helper->redirector('index');
        }
    }
}