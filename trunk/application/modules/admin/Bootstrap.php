<?php

class Admin_Bootstrap extends Zend_Application_Module_Bootstrap
{
    protected function _initRouting()
    {
        $Config = new Zend_Config_Ini(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR . 'routes.ini', 'production');
        $Router = Zend_Controller_Front::getInstance()->getRouter();

        $Router->addConfig($Config, 'routes');
    }
}

?>