<?php

class App_Model_DbTable_Gallery extends Zend_Db_Table_Abstract
{
    protected $_name = 'foto';
    protected $_primary = 'IDFoto';

    protected $_rewrite = array('IDFoto' => 'IDAlbum',
                                'IDPadre' => 'IDPage',
                                'Titolo' => 'Title',
                                'Descrizione' => 'Description',
                                'Estensione' => 'Extension',
                                'Data' => 'Date');

    protected $_rewritePhoto = array('IDFoto' => 'IDPhoto',
                                'IDPadre' => 'IDAlbum',
                                'Titolo' => 'Title',
                                'Descrizione' => 'Description',
                                'Estensione' => 'Extension',
                                'Data' => 'Date');

    public function addAlbum($Title, $Description, $IDPage)
    {
        $Data = array('Titolo' => $Title, 'Descrizione' => $Description, 'IDPadre' => $IDPage, 'Data' => new Zend_Db_Expr('NOW()'));

        return $this->insert($Data);
    }

    public function updateAlbum($IDAlbum, $Title, $Description, $IDPage)
    {
        $Data = array('Titolo' => $Title, 'Descrizione' => $Description, 'IDPadre' => $IDPage);

        return $this->update($Data, "IDFoto = '$IDAlbum' AND Estensione = ''");
    }

    public function deleteAlbum($IDAlbum)
    {
        return $this->delete("IDFoto = '$IDAlbum' AND Estensione = ''");
    }

    public function getAlbumsFromPage($IDPage)
    {
        $Albums = $this->fetchAll("IDPadre = '$IDPage' AND Estensione = ''");

        return $Albums ? $Albums->toArray() : array();
    }

    public function getAlbum($IDAlbum)
    {
        $IDAlbum = (int) $IDAlbum;
        $Album = $this->fetchRow("IDFoto = '$IDAlbum' AND Estensione = ''");

        if(!$Album)
            throw new Exception("Could not find album $IDAlbum");

        return Zwe_Db_Table_Row::rewrite($Album, $this->_rewrite);
    }

    public function getPhoto($IDPhoto)
    {
        $IDPhoto = (int) $IDPhoto;
        $Photo = $this->fetchRow("IDFoto = '$IDPhoto' AND Estensione != ''");

        if(!$Photo)
            throw new Exception("Could not find photo $IDPhoto");

        return Zwe_Db_Table_Row::rewrite($Photo, $this->_rewritePhoto);
    }

    public function addPhoto($IDAlbum, $Extension, $Title)
    {
        $this->getAlbum($IDAlbum);

        $Data = array('IDPadre' => (int) $IDAlbum, 'Estensione' => $Extension, 'Titolo' => $Title, 'Data' => new Zend_Db_Expr('NOW()'));
        return $this->insert($Data);
    }

    public function updatePhoto($IDPhoto, $Title = null, $Description = null, $Extension = null, $IDAlbum = null, $Ratio = null)
    {
        $this->getPhoto($IDPhoto);
        $Data = array();

        if($Title)
            $Data['Titolo'] = $Title;
        if($Description)
            $Data['Descrizione'] = $Description;
        if($Extension)
            $Data['Estensione'] = $Extension;
        if($IDAlbum)
        {
            $this->getAlbum($IDAlbum);
            $Data['IDPadre'] = $IDAlbum;
        }
        if($Ratio)
            $Data['Ratio'] = $Ratio;

        return $this->update($Data, "IDFoto = '$IDPhoto'");
    }

    public function getPhotos($IDAlbum)
    {
        $this->getAlbum($IDAlbum);

        return $this->fetchAll('IDPadre = ' . (int) $IDAlbum, 'IDFoto');
    }

    public function deletePhotos($IDPhotos)
    {
        $Ret = 0;

        if(is_array($IDPhotos))
            foreach($IDPhotos as $IDPhoto)
                $Ret += $this->deletePhoto($IDPhoto);
        else
            $Ret += $this->deletePhoto($IDPhotos);

        return $Ret;
    }

    public function deletePhoto($IDPhoto)
    {
        return $this->delete("IDFoto = '$IDPhoto' AND Estensione != ''");
    }
}

