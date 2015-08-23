<?php

class Zwe_View_Helper_Js extends Zend_View_Helper_Abstract
{
    protected $_defaultJS = array('js/mootools/core', 'js/mootools/more');
    protected $_baseurl = '';

    public function __construct()
    {
        $URL = Zend_Controller_Front::getInstance()->getRequest()->getBaseUrl();
        $Root = '/' . trim($URL, '/');

        if('/' == $Root)
            $Root = '';

        $this->_baseurl = $Root . '/';
    }

    public function js($File = false, $Absolute = false)
    {
		if($File)
		{
			if(is_string($File))
				$this->_defaultJS[] = ($Absolute ? '' : 'js/') . $File;
			elseif(is_array($File))
				foreach($File as $F)
					$this->js($F, $Absolute);
		}
		else
			foreach($this->_defaultJS as $JS)
			{
                $this->view->headScript()->appendFile($this->_baseurl . $JS . '.js');
			}
    }
}

?>