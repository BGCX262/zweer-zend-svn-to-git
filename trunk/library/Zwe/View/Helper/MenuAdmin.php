<?php

class Zwe_View_Helper_MenuAdmin extends Zend_View_Helper_Abstract
{
    protected $_menu_link = array('Home' => 'index', 'news', 'pages', 'gallery', 'events');

    public function menuAdmin()
    {
        $MenuStr  = '<ul>';
        foreach($this->_menu_link as $K => $M)
            $MenuStr .= '<li><a href="' . $this->view->url(array('controller' => $M,
                                                                 'module' => 'admin',
                                                                 'action' => 'index'), 'default') . '" title="' . (is_int($K) ? ucfirst($M) : $K) . '">' . (is_int($K) ? ucfirst($M) : $K) . '</a></li>';
        $MenuStr .= '</ul>';

        return $MenuStr;
    }
}

?>