<?php

class App_Model_DbTable_Pages extends Zend_Db_Table_Abstract
{
    protected $_name = 'pagine';
    protected $_primary = 'IDPagina';

    protected $_rewrite = array('IDPagina' => 'IDPage',
                                'IDPadre' => 'IDParent',
                                'Titolo' => 'Title',
                                'Testo' => 'Text',
                                'Tipo' => 'Type');

    public function getPageFromURL(&$Params, $IDParent = 0)
    {
        if(is_array($Params))
        {
            $OldPage = array();
            $URL = array_shift($Params);
            while($Page = $this->getPageFromURL($URL, $IDParent))
            {
                $IDParent = $Page['IDPagina'];
                $OldPage = $Page;
                $URL = array_shift($Params);
            }

            if($URL)
                array_unshift($Params, $URL);

            return $OldPage && !$URL ? $OldPage : false;
        }
        else
        {
            $Page = $this->fetchRow("URL = '$Params' AND IDPadre = '$IDParent'");
            return $Page ? $Page->toArray() : false;
        }
    }

    public function getPageFromID($IDPage)
    {
        $Page = $this->fetchRow("IDPagina = '$IDPage'");

        return $Page ? $Page->toArray() : false;
    }

    public function getPagesFromParent($IDParent)
    {
        return $this->fetchAll("IDPadre = '$IDParent'");
    }

    public function getPagesFromType($Type)
    {
		$Pages = $this->fetchAll("Tipo = '$Type'");
        return $Pages ? $Pages->toArray() : array();
    }

    public function getNewsPages()
    {
        return $this->getPagesFromType('news');
    }

    public function getStaticPages()
    {
        return $this->getPagesFromType('pages');
    }

    public function getGalleryPages()
    {
        return $this->getPagesFromType('gallery');
    }

    public function getPagesWithGallery()
    {
        $Select = $this->select()->setIntegrityCheck(false)
                                 ->from('pagine')
                                 ->join('foto', "foto.IDPadre = pagine.IDPagina AND foto.Estensione = ''", array('TitoloAlbum' => 'Titolo', 'IDAlbum' => 'IDFoto'))
                                 ->order("pagine.Titolo");
        $Pages = $this->fetchAll($Select);

        return $Pages ? $Pages->toArray() : array();
    }

    public function getMainMenu()
    {
        return $this->getMenu();
    }

    public function getMenu($IDParent = 0)
    {
        $Pages = $this->fetchAll("IDPadre = '$IDParent'");

        if($Pages)
        {
            $Pages = $Pages->toArray();
            for($I = 0; $I < count($Pages); ++$I)
            {
                $Pages[$I]['URI'] = $this->getURI($Pages[$I]);
            }

            return $Pages;
        }

        return array();
    }

    public function getURI($Page)
    {
        $URI = $Page['URL'];

        while($Page['IDPadre'] != 0)
        {
            $Page = $this->getPageFromID($Page['IDPadre']);
            $URI = $Page['URL'] . '/' . $URI;
        }

        $BaseURL = Zend_Controller_Front::getInstance()->getRequest()->getBaseUrl();
        $BaseURL = '/' . trim($BaseURL, '/');
        if('/' == $BaseURL)
            $BaseURL = '';

        return $BaseURL . '/pages/' . $URI;
    }

    public function getTreePages($IDParent = 0)
    {
        $Pages = $this->getPagesFromParent($IDParent);
        if($Pages)
            $Pages = $Pages->toArray();
        else
            return array();

        for($I = 0; $I < count($Pages); ++$I)
        {
            $Pages[$I]['Figli'] = $this->getTreePages($Pages[$I]['IDPagina']);
        }

        return $Pages;
    }

    public function getPage($IDPage)
    {
        $IDPage = (int) $IDPage;
        $Page = $this->fetchRow("IDPagina = '$IDPage'");

        if(!$Page)
            throw new Exception("Could not find page $IDPage");

        return Zwe_Db_Table_Row::rewrite($Page, $this->_rewrite);
    }

    public function addPage($Title, $URL, $Type, $Text, $IDParent)
    {
        $Data = array('Titolo' => $Title, 'URL' => $URL, 'Tipo' => $Type, 'Testo' => $Text, 'IDPadre' => $IDParent);

        return $this->insert($Data);
    }

    public function updatePage($IDPage, $Title, $URL, $Type, $Text, $IDParent)
    {
        $Data = array('Titolo' => $Title, 'URL' => $URL, 'Tipo' => $Type, 'Testo' => $Text);
        if($IDPage != $IDParent)
            $Data['IDPadre'] = $IDParent;

        $this->update($Data, "IDPagina = '$IDPage'");
    }

    public function deletePage($IDPage)
    {
        $this->delete("IDPagina = '$IDPage'");
    }
}

