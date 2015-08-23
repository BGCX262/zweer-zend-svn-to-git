<?php

class Admin_NewsController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $News = new App_Model_DbTable_News();
		$Pages = new App_Model_DbTable_Pages();
		$this->view->pages = $Pages->getNewsPages();
		for($I = 0; $I < count($this->view->pages); ++$I)
			$this->view->pages[$I]['News'] = $News->getNewsFromPage($this->view->pages[$I]['IDPagina']);
		$this->view->news = $News->getNewsFromPage(0);
    }

    public function addAction()
    {
		$News = new App_Model_DbTable_News();
		$Page = new App_Model_DbTable_Pages();

        $Form = new Admin_Form_News();
		$Form->Submit->setLabel('Add');
		$Form->IDPage->addMultiOptions($this->view->printSelectPages($Page->getNewsPages()));
		$this->view->form = $Form;

		if($this->getRequest()->isPost())
		{
			$FormData = $this->getRequest()->getPost();

			if($Form->isValid($FormData))
			{
				$Title = $Form->getValue('Title');
				$Text = $Form->getValue('Text');
				$IDPage = $Form->getValue('IDPage');

				$News->addNews($Title, $Text, $IDPage);

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
		$News = new App_Model_DbTable_News();
		$Page = new App_Model_DbTable_Pages();

        $Form = new Admin_Form_News();
		$Form->Submit->setLabel('Edit');
		$Form->IDPage->addMultiOptions($this->view->printSelectPages($Page->getNewsPages()));
		$this->view->form = $Form;

		if($this->getRequest()->isPost())
		{
			$FormData = $this->getRequest()->getPost();

			if($Form->isValid($FormData))
			{
				$IDNews = (int) $Form->getValue('IDNews');
				$Title = $Form->getValue('Title');
				$Text = $Form->getValue('Text');
				$IDPage = $Form->getValue('IDPage');

				$News->updateNews($IDNews, $Title, $Text, $IDPage);

				$this->_helper->redirector('index');
			}
			else
			{
				$Form->populate($FormData);
			}
		}
		else
		{
			$IDNews = (int) $this->_getParam('IDNews', 0);
			if($IDNews)
			{
				$News = new App_Model_DbTable_News();
				$Form->populate($News->getNews($IDNews));
			}
		}
    }

    public function deleteAction()
    {
        $IDNews = (int) $this->_getParam('IDNews', 0);
		if($IDNews)
		{
			$News = new App_Model_DbTable_News();
			$News->deleteNews($IDNews);

			$this->_helper->redirector('index');
		}
    }
}