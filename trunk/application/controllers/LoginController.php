<?php

class LoginController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $Form = new App_Form_Login();
        $Request = $this->getRequest();

        if($Request->isPost())
        {
            if($Form->isValid($Request->getPost()))
            {
                if($this->_process($Form->getValues()))
                {
                    # We are authenticated
                    $this->_helper->redirector('index', 'index');
                }
            }
        }

        $this->view->form = $Form;
    }

	public function logoutAction()
	{
		Zend_Auth::getInstance()->clearIdentity();
		$this->_helper->redirector('index', 'index');
	}

	public function preDispatch()
	{
		if(Zend_Auth::getInstance()->hasIdentity())
		{
			# L'utente è già loggato quindi, a meno che non voglia fare il logout, viene rediretto all'homepage
			if('logout' != $this->getRequest()->getActionName())
				$this->_helper->redirector('index', 'index');
		}
		else
		{
			# L'utente non è loggato, quindi se vuole fare il logout viene rediretto al form di login
			if('logout' == $this->getRequest()->getActionName())
				$this->_helper->redirector('index');
		}
	}

	protected function _process(array $Values)
    {
        $Adapter = $this->_getAuthAdapter();
        $Adapter->setIdentity($Values['email']);
        $Adapter->setCredential($Values['password']);

        $Auth = Zend_Auth::getInstance();
        $Result = $Auth->authenticate($Adapter);

        if($Result->isValid())
        {
            $User = $Adapter->getResultRowObject();
            $Auth->getStorage()->write($User);
            return true;
        }

        return false;
    }

    protected function _getAuthAdapter()
    {
        $DbAdapter = Zend_Db_Table::getDefaultAdapter();
        $AuthAdapter = new Zend_Auth_Adapter_DbTable($DbAdapter);

        $AuthAdapter->setTableName('utenti')
                    ->setIdentityColumn('Email')
                    ->setCredentialColumn('Password')
                    ->setCredentialTreatment('SHA1(CONCAT(?, Salt))');

        return $AuthAdapter;
    }
}

