<?php

class Admin_Form_Pages extends Zend_Form
{
    public function init()
    {
        $this->setName('pages');

        $this->addElement('hidden', 'IDPage', array(
                                      'filters' => array('Int')
                                              ));
        $this->addElement('text', 'Title', array(
                                    'label' => 'Title',
                                    'required' => true,
                                    'filters' => array(
                                        'StripTags',
                                        'StringTrim'
                                    )
                                           ));
        $this->addElement('text', 'URL', array(
                                    'label' => 'URL',
                                    'required' => true,
                                    'filters' => array(
                                        'StripTags',
                                        'StringTrim'
                                    )
                                         ));
        $SelectParent = new Zend_Form_Element_Select('IDParent', array(
                'label' => 'Parent page',
                'filters' => array('Int')
                                                                 ));
        $SelectParent->addMultiOption('0', 'Root');
        $this->addElement($SelectParent);
        
        $SelectType = new Zend_Form_Element_Select('Type', array(
                                      'label' => 'Type',
                                      'required' => true
                                            ));
        $SelectType->addMultiOption('', 'Select...')
                   ->addMultiOption('pages', 'Static Page')
                   ->addMultiOption('news', 'News Page')
                   ->addMultiOption('gallery', 'Photo gallery');
        $this->addElement($SelectType);

        $this->addElement('textarea', 'Text', array(
                                        'label' => 'Text',
                                        'required' => true,
                                        'filters' => array('StringTrim'),
										'attribs' => array('id' => 'form_pages_text')
                                              ));
        $this->addElement('submit', 'Submit');
    }
}

