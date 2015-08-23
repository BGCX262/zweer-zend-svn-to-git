<?php

class Zwe_Controller_Image
{
    private $Image;
    private $Type;

    public function __construct($Filename = false)
    {
        if($Filename)
            $this->load($Filename);
    }

    public function load($Filename)
    {
        $Info = getimagesize($Filename);
        $this->Type = $Info[2];

        if(IMAGETYPE_JPEG == $this->Type)
            $this->Image = imagecreatefromjpeg($Filename);
        elseif(IMAGETYPE_GIF == $this->Type)
            $this->Image = imagecreatefromgif($Filename);
        elseif(IMAGETYPE_PNG == $this->Type)
            $this->Image = imagecreatefrompng($Filename);

        return $this;
    }

    public function save($Filename, $Type = false, $Compression = 75, $Permission = false)
    {
        if(!$Type)
            $Type = $this->Type;

        if(IMAGETYPE_JPEG == $Type)
            imagejpeg($this->Image, $Filename, $Compression);
        elseif(IMAGETYPE_GIF == $Type)
            imagegif($this->Image, $Filename);
        elseif(IMAGETYPE_PNG == $Type)
            imagepng($this->Image, $Filename);

        if($Permission)
            chmod($Filename, $Permission);

        return $this;
    }

    public function output($Type)
    {
        if(IMAGETYPE_JPEG == $Type)
            imagejpeg($this->Image);
        elseif(IMAGETYPE_GIF == $Type)
            imagegif($this->Image);
        elseif(IMAGETYPE_PNG == $Type)
            imagepng($this->Image);
    }

    public function getWidth()
    {
        return imagesx($this->Image);
    }

    public function getHeight()
    {
        return imagesy($this->Image);
    }

    public function getRatio()
    {
        return round($this->getWidth() / $this->getHeight(), 2);
    }

    public function resizeToHeight($Height, $Force = false)
    {
        if(!$Force && $Height > $this->getHeight())
            return $this;

        $Ratio = $Height / $this->getHeight();
        $Width = intval($this->getWidth() * $Ratio);
        $this->resize($Width, $Height);

        return $this;
    }

    public function resizeToWidth($Width, $Force = false)
    {
        if(!$Force && $Width > $this->getWidth())
            return $this;

        $Ratio = $Width / $this->getWidth();
        $Height = intval($this->getHeight() * $Ratio);
        $this->resize($Width, $Height);

        return $this;
    }

    public function resizeTo($Width, $Height, $Force = false)
    {
        $this->resizeToWidth($Width, $Force);
        $this->resizeToHeight($Height, $Force);

        return $this;
    }

    public function scale($Scale)
    {
        $Ratio = $Scale / 100;
        $Width = $this->getWidth() * $Ratio;
        $Heigth = $this->getHeight() * $Ratio;
        $this->resize($Width, $Heigth);

        return $this;
    }

    public function resize($Width, $Height)
    {
        if($this->Type == IMAGETYPE_GIF)
        {
            $Image = imagecreate($Width, $Height);
            imagecopyresized($Image, $this->Image, 0, 0, 0, 0, $Width, $Height, $this->getWidth(), $this->getHeight());
        }
        else
        {
            $Image = imagecreatetruecolor($Width, $Height);
            imagecopyresampled($Image, $this->Image, 0, 0, 0, 0, $Width, $Height, $this->getWidth(), $this->getHeight());
        }

        $this->Image = $Image;

        return $this;
    }
}

?>