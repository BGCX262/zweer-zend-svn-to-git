<?php

class App_Model_DbTable_Messages extends Zend_Db_Table_Abstract
{
    protected $_name = 'messaggi';
	protected $_primary = 'IDMessaggio';

	protected $_rewrite = array('IDMessaggio' => 'IDMessage',
								'IDPadre' => 'IDParent',
								'IDMittente' => 'IDAuthor',
								'Testo' => 'Text',
								'Data' => 'Date',
								'NomeUtente' => 'AuthorName',
								'CognomeUtente' => 'AuthorSurename');

	public function createMessage($Text, $Receivers)
	{
		$Data = array('IDMittente' => Zend_Auth::getInstance()->getIdentity()->IDUtente, 'Testo' => $Text, 'Data' => new Zend_Db_Expr('NOW()'));

		$IDMessage = $this->insert($Data);
        $this->update(array('IDPadre' => $IDMessage), "IDMessaggio = '$IDMessage'");

		$MessageUser = new App_Model_DbTable_MessagesUsers();
		$MessageUser->addReceivers($IDMessage, $Receivers);
	}

	public function replyMessage($IDParent, $Text)
	{
		$Data = array('IDMittente' => Zend_Auth::getInstance()->getIdentity()->IDUtente, 'IDPadre' => $IDParent, 'Testo' => $Text, 'Data' => new Zend_Db_Expr('NOW()'));

		$this->insert($Data);
	}

	public function getMessage($IDMessage, $IDUser = false)
	{
		$MessageUser = new App_Model_DbTable_MessagesUsers();

		if($MessageUser->existMessage($IDMessage, $IDUser))
		{
			$Select = $this->select()->setIntegrityCheck(false)
									 ->from('messaggi')
									 ->join('utenti',
											'utenti.IDUtente = messaggi.IDMittente',
											array('NomeUtente' => 'Nome', 'CognomeUtente' => 'Cognome', 'Utente' => 'CONCAT(Nome, \' \', Cognome)'))
									 ->where("IDMessaggio = '$IDMessage' OR IDPadre = '$IDMessage'")
									 ->order('Data DESC');
			return $this->fetchAll($Select);
		}
		else
			return false;
	}

	public function getAllMessages($IDUser = false)
	{
		if(!$IDUser)
			$IDUser = Zend_Auth::getInstance()->getIdentity()->IDUtente;

		$Select = $this->select()->setIntegrityCheck(false)
								 ->from('messaggi')
								 ->join('messaggi_utenti', 'messaggi_utenti.IDMessaggio = messaggi.IDPadre', array())
								 ->join('utenti',
										'utenti.IDUtente = messaggi.IDMittente',
										array('NomeUtente' => 'Nome', 'CognomeUtente' => 'Cognome', 'Utente' => 'CONCAT(Nome, \' \', Cognome)'))
								 ->where("messaggi_utenti.IDUtente = '$IDUser'")
								 ->where("messaggi.Data = (select max(Data) from messaggi as M WHERE M.IDPadre = messaggi.IDPadre)");
		$Messages = $this->fetchAll($Select);

		return $Messages;
	}
}

