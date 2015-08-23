<?php

class Admin_Form_News extends Zend_Form
{
    public function init()
    {
        $this->setName('news');

		$this->addElement('hidden', 'IDNews', array('filters' => array('Int')));
		$this->addElement('text', 'Title', array('label' => 'Title',
											   	 'required' => true,
											     'filters' => array('StripTags',
																	'StringTrim')));
		$SelectPage = new Zend_Form_Element_Select('IDPage', array('label' => 'Page',
																   'filters' => array('Int'),
															  	   'required' => true));
		$SelectPage->addMultiOption('', 'Select...');
		$this->addElement($SelectPage);

		$this->addElement('textarea', 'Text', array('label' => 'Text',
											  		'required' => true,
											  		'filters' => array('StringTrim'),
											  		'attribs' => array('id' => 'form_news_text')));

		$this->addElement('submit', 'Submit');
    }
}

