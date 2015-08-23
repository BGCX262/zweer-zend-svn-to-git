<?php

class Zwe_View_Helper_Img extends Zend_View_Helper_Abstract
{
	protected $_baseurl = '';
	protected $_exists = array();

	const NoImg = 'data:image/gif;base64,R0lGODlhFAAUAIAAAAAAAP///yH5BAAAAAAALAAAAAAUABQAAAI5jI+pywv4DJiMyovTi1srHnTQd1BRSaKh6rHT2cTyHJqnVcPcDWZgJ0oBV7sb5jc6KldHUytHi0oLADs=';

	public function __construct()
	{
		$URL = Zend_Controller_Front::getInstance()->getRequest()->getBaseUrl();
		$Root = '/' . trim($URL, '/');

		if('/' == $Root)
			$Root = '';

		$this->_baseurl = $Root . '/';
	}

	public function img($Path, $Params = array())
	{
		$PList = array();
		$Image = $this->_baseurl . ltrim($Path, '/');
		$RealPath = realpath(PUBLIC_PATH . '/' . $Image);

		if(!isset($this->_exists[$Path]))
			$this->_exists[$Path] = file_exists($RealPath);

		if(!isset($Params['alt']))
			$Params['alt'] = '';

		foreach($Params as $Param => $Value)
			$PList[] = $Param . '="' . $this->view->escape($Value) . '"';

		$ParamStr = ' ' . implode(' ', $PList);

		return '<img src="' . ($this->_exists[$Path] ? $Image : self::NoImg) . '"' . $ParamStr . '/>';
	}
}

?>