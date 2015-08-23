<?php

class NewsController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->page = $this->_getParam('Page');
    }

    public function indexAction()
    {
        $News = new App_Model_DbTable_News();
        $this->view->news = $News->getNewsFromPage($this->view->page['IDPagina']);
    }
}