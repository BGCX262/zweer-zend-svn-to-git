<?php

class PagesController extends Zend_Controller_Action
{
    public function init()
    {
    }

    public function indexAction()
    {
        $Params = $this->_getParam('params');
        $ThisPage = $this->_getParam('Page');

        $Page = new App_Model_DbTable_Pages();
        $this->view->page = $Page->getPageFromID($ThisPage['IDPagina']);
    }
}



