<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $Model = new App_Model_Color();
		$this->view->color = $Model->getRandomColor();
    }


}

