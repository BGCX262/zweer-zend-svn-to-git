<?php

class MessagesController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $Messages = new App_Model_DbTable_Messages();
		$this->view->messages = $Messages->getAllMessages();
    }

    public function createAction()
    {
		$Form = new App_Form_Messages();
		$Form->addReceivers();
		$this->view->form = $Form;

        if($this->getRequest()->isPost())
		{
			$FormData = $this->getRequest()->getPost();

			if($Form->isValid($FormData))
			{
				$Receivers = explode(',', $Form->getValue('receivers'));
				$Text = $Form->getValue('text');

				$Message = new App_Model_DbTable_Messages();
				$Message->createMessage($Text, $Receivers);

				$this->_helper->redirector('index');
			}
			else
			{
				$Form->populate($FormData);
			}
		}
    }

    public function viewAction()
    {
		$IDMessage = $this->_getParam('IDMessage', 0);
		if(!$IDMessage)
		{
			$this->_helper->redirector('index');
			return;
		}

		$Messages = new App_Model_DbTable_Messages();

		$Form = new App_Form_Messages();
		$Form->addID($IDMessage);
		$this->view->form = $Form;

		if($this->getRequest()->isPost())
		{
			$FormData = $this->getRequest()->getPost();

			if($Form->isValid($FormData))
			{
				$IDParent = (int) $Form->getValue('id_parent');

				if($IDParent)
				{
					$Text = $Form->getValue('text');

					$Messages->replyMessage($IDParent, $Text);

					$Form->getElement('text')->setValue('');
				}
			}
		}

		$this->view->messages = $Messages->getMessage($IDMessage);
    }
}







