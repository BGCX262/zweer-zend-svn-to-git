<?php

class Admin_Form_Album extends Zend_Form
{
    public function init()
    {
        $this->setName('album');

        $this->addElement('hidden', 'IDAlbum', array('filters' => array('Int')));
        $this->addElement('text', 'Title', array('label' => 'Title',
                                                 'required' => true,
                                                 'filters' => array('StripTags',
                                                                    'StringTrim')));
        $SelectPage = new Zend_Form_Element_Select('IDPage', array('label' => 'Page',
                                                                   'filters' => array('Int'),
                                                                   'required' => true));
        $SelectPage->addMultiOption('', 'Select...');
        $this->addElement($SelectPage);

        $this->addElement('textarea', 'Description', array('label' => 'Description',
                                                           'required' => true,
                                                           'filters' => array('StringTrim')));

        $this->addElement('submit', 'Submit');
    }
}
