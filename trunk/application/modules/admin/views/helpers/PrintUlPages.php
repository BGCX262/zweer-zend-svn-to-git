<?php

class Admin_View_Helper_PrintUlPages extends Zend_View_Helper_Abstract
{
    public function printUlPages($Pages)
    {
        if(!$Pages)
            return '';

        $Ret  = '<ul>';
        foreach($Pages as $Page)
        {
            $Ret .= '<li>';
            $Ret .= '<a href="' . $this->view->url(array('IDPage' => $Page['IDPagina']), 'pagesEdit') . '" title="Edit page">' . $this->view->img('images/icons/edit_16x16.png', array('alt' => 'Edit')) . '</a> ';
            $Ret .= '<a href="' . $this->view->url(array('IDPage' => $Page['IDPagina']), 'pagesDelete') . '" title="Delete page" onclick="return confirm(\'Are you sure you want to delete this page?\');">' . $this->view->img('images/icons/delete_16x16.png', array('alt' => 'Delete')) . '</a> ';
            $Ret .= $Page['Titolo'];
            $Ret .= $this->printUlPages($Page['Figli']);
            $Ret .= '</li>';
        }
        $Ret .= '</ul>';

        return $Ret;
    }
}

?>