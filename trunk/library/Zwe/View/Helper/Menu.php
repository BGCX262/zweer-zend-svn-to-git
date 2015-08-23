<?php

class Zwe_View_Helper_Menu extends Zend_View_Helper_Abstract
{
    public function menu()
    {
        $Pages = new App_Model_DbTable_Pages();
        $Menu = $Pages->getMainMenu();
        array_unshift($Menu, array('Titolo' => 'Home', 'URI' => '/'));

        $MenuStr  = '<ul>';
        foreach($Menu as $M)
            $MenuStr .= '<li><a href="' . $M['URI'] . '" title="' . $M['Titolo'] . '">' . $M['Titolo'] . '</a></li>';
        $MenuStr .= '</ul>';

        return $MenuStr;
    }
}

?>