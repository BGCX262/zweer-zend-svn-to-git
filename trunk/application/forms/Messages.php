<?php

class App_Form_Messages extends Zend_Form
{
	public function init()
	{
		$this->setName('messages');
		$this->setMethod('post');

		$this->addElement('textarea', 'text', array(
			'filters' => array('StringTrim'),
			'required' => true,
			'order' => 20
											  ));

		$this->addElement('submit', 'submit', array(
			'label' => 'Send',
			'order' => 30
											  ));
	}

	public function addReceivers()
	{
		$this->addElement('text', 'receivers', array(
			'filters' => array('StringTrim'),
			'required' => true,
			'label' => 'Receivers:',
			'order' => 10
											   ));

		$this->getElement('text')->setLabel('Text:');
	}

	public function addID($IDMessage)
	{
		$this->addElement('hidden', 'id_parent', array(
			'filters' => array('Int'),
			'required' => true,
			'value' => $IDMessage
												 ));
	}
}

?>