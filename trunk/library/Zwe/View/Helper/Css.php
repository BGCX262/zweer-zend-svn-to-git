<?php

class Zwe_View_Helper_Css extends Zend_View_Helper_Abstract
{
    protected $_defaultCSS = array('css/frontend/main', 'css/frontend/container');
    protected $_baseurl = '';

    public function __construct()
    {
        $URL = Zend_Controller_Front::getInstance()->getRequest()->getBaseUrl();
        $Root = '/' . trim($URL, '/');

        if('/' == $Root)
            $Root = '';

        $this->_baseurl = $Root . '/';
    }

    public function css($File = false, $Absolute = false)
    {
		if($File)
		{
			if(is_string($File))
				$this->_defaultCSS[] = ($Absolute ? '' : 'css/') . $File;
			elseif(is_array($File))
				foreach($File as $F)
					$this->css($F, $Absolute);
		}
		else
			foreach($this->_defaultCSS as $CSS)
			{
                $this->view->headLink()->appendStylesheet($this->_baseurl . $CSS . '.css');
			}
    }
}

?>