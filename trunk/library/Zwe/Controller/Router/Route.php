<?php

class Zwe_Controller_Router_Route implements Zend_Controller_Router_Route_Interface
{
    public function __construct()
    {
        
    }

    public function match($Path, $Partial = false)
    {
        $Path = trim($Path, '/');
        $Params = explode('/', $Path);

        if(array_shift($Params) == 'pages')
        {
            $Pages = new App_Model_DbTable_Pages();
            $Page = $Pages->getPageFromURL($Params);

            if($Page)
                return array(
                    'controller' => $Page['Tipo'],
                    'action' => 'index',
                    'params' => $Params,
                    'Page' => $Page
                );
        }

        return false;
    }

    public function assemble($Data = array(), $Reset = false, $Encode = false)
    {
        return '';
    }

    public static function getInstance(Zend_Config $Config)
    {
        return new Zwe_Controller_Router_Route();
    }
}

?>