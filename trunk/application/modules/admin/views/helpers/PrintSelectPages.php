<?php

class Admin_View_Helper_PrintSelectPages extends Zend_View_Helper_Abstract
{
    public function printSelectPages($Pages, $Level = 1)
    {
        if(!$Pages)
            return array();

        $Ret = array();
        foreach($Pages as $Page)
        {
            $Ret[] = array('value' => str_repeat('-', $Level) . " $Page[Titolo]", 'key' => $Page['IDPagina']);
            $Ret = array_merge($Ret, $this->printSelectPages($Page['Figli'], $Level + 1));
        }

        return $Ret;
    }
}

?>