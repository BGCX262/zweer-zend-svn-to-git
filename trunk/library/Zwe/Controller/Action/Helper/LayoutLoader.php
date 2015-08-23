<?php

class Zwe_Controller_Action_Helper_LayoutLoader extends Zend_Controller_Action_Helper_Abstract
{
	public function preDispatch()
	{
		$Bootstrap = $this->getActionController()->getInvokeArg('bootstrap');
		$Config = $Bootstrap->getOptions();
		$Module = $this->getRequest()->getModuleName();

		if(isset($Config[$Module]['resources']['layout']['layout']))
		{
			$LayoutScript = $Config[$Module]['resources']['layout']['layout'];
			$this->getActionController()->getHelper('layout')->setLayout($LayoutScript);
		}
	}
}

?>