<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Imposta l'auto-load per le classi della libreria Zwe.
     * @return Zend_Application_Module_Autoloader
     */
	protected function _initAppAutoload()
	{
		$Autoloader = new Zend_Application_Module_Autoloader(array(
			'namespace' => 'App',
			'basePath' => __DIR__
															 ));
		$Autoloader->addResourceType('zwe', dirname(__DIR__) . '/library/Zwe', 'Zwe');

		return $Autoloader;
	}

    /**
     * Aggiunge il path della libreria Zwe agli helper.
     * @return void
     */
	protected function _initViewHelpers()
	{
		$this->_bootstrap('view');
		$View = $this->getResource('view');

		$View->addHelperPath('Zwe/View/Helper', 'Zwe_View_Helper');
	}

    /**
     * Decide il layout, se default o admin.
     * @todo Modificare la classe che viene richiamata per farle prendere anche i layout json e ajax.
     * @return void
     */
	protected function _initLayoutHelper()
	{
		$this->bootstrap('frontController');
		Zend_Controller_Action_HelperBroker::addHelper(new Zwe_Controller_Action_Helper_LayoutLoader());
	}

    /**
     * Setta l'encoding del sito.
     * Se non fosse fatto, il database non sarebbe allineato e di conseguenza al posto degli accenti verrebbero scritte strane.
     * @return void
     */
	protected function _initEncoding()
	{
		$this->_bootstrap('view');
		$View = $this->getResource('view');

		$View->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');
	}

    /**
     * Inizializza tutto il motore di routing.
     * @todo Impostare un routing decente, tenendo conto anche degli ajax e dei json!
     * @return void
     */
    protected function _initRouting()
    {
        $Config = new Zend_Config_Ini(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR . 'routes.ini', 'production');
        $Router = Zend_Controller_Front::getInstance()->getRouter();

        $Router->addConfig($Config, 'routes');
    }
}

