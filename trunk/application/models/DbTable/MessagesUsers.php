<?php

class App_Model_DbTable_MessagesUsers extends Zend_Db_Table_Abstract
{
    protected $_name = 'messaggi_utenti';
	protected $_primary = 'IDMessaggiUtenti';

	protected $_rewrite = array('IDMessaggiUtenti' => 'IDMessagesUsers',
								'IDMessaggio' => 'IDMessage',
								'IDUtente' => 'IDUser',
								'Letto' => 'Read',
								'Data' => 'Date');

	public function addReceivers($IDMessage, array $Receivers)
	{
		$Myself = Zend_Auth::getInstance()->getIdentity()->IDUtente;
		array_push($Receivers, $Myself);
		$Receivers = array_unique($Receivers);

		foreach($Receivers as $Receiver)
			$this->addReceiver($IDMessage, $Receiver);
	}

	protected function addReceiver($IDMessage, $Receiver)
	{
		$Data = array('IDMessaggio' => $IDMessage, 'IDUtente' => $Receiver, 'Data' => new Zend_Db_Expr('NOW()'));

		$this->insert($Data);
	}

	public function getMyMessages($IDUser = false)
	{
		if(!$IDUser)
			$IDUser = Zend_Auth::getInstance()->getIdentity()->IDUtente;

		return $this->fetchAll("IDUtente = '$IDUser'");
	}

	public function existMessage($IDMessage, $IDUser = false)
	{
		if(!$IDUser)
			$IDUser = Zend_Auth::getInstance()->getIdentity()->IDUtente;

		$MessageUser = $this->fetchRow("IDUtente = '$IDUser' AND IDMessaggio = '$IDMessage'");
		return $MessageUser && true;
	}
}

