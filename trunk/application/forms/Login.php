<?php

class App_Form_Login extends Zend_Form
{
	public function init()
	{
        $this->setName('login');
        $this->setMethod('post');

		$this->addElement('text', 'email', array(
			'filters' => array('StringTrim'),
			'required' => true,
			'label' => 'Email Address:'
												 ));
		$this->addElement('password', 'password', array(
			'filters' => array('StringTrim'),
			'required' => true,
			'label' => 'Password:'
												        ));

		$this->addElement('submit', 'login', array(
			'label' => 'Login'
												   ));
	}
}

?>