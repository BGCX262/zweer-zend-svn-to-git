<?php

class App_View_Helper_LoggedInAs extends Zend_View_Helper_Abstract
{
    public function loggedInAs()
    {
        $Auth = Zend_Auth::getInstance();
		$Ret = '';

        if($Auth->hasIdentity())
        {
            $Username = $Auth->getIdentity()->Nome . ' ' . $Auth->getIdentity()->Cognome;
            $Module = Zend_Controller_Front::getInstance()->getRequest()->getModuleName();

            $LogoutURL = $this->view->url(array(
                'controller' => 'login',
                'action' => 'logout',
                'module' => 'default'
                                          ), 'default', true);

			$MessaggiURL = $this->view->url(array(
				'controller' => 'messages',
				'action' => 'index',
                'module' => 'default'
											), 'default');

            $AdminURL = $this->view->url(array(
                'controller' => 'index',
                'action' => 'index',
                'module' => $Module == 'admin' ? 'default' : 'admin'
                                         ), 'default');

			#$Ret .= 'Welcome ' . $Username . ' ';
            $Ret .= '<a href="' . $MessaggiURL . '">' . $this->view->img('images/icons/mail_24x24.png', array('title' => 'Messages', 'alt' => 'Messages')) . '</a> ';
            $Ret .= '<a href="' . $AdminURL . '">' . $this->view->img('images/icons/' . ($Module == 'admin' ? 'magic_wand' : 'wrench') . '_24x24.png',
                                                                      array('title' => $Module == 'admin' ? 'Public' : 'Admin',
                                                                            'alt' => $Module == 'admin' ? 'Public' : 'Admin')) . '</a> ';
			$Ret .= '<a href="' . $LogoutURL . '">' . $this->view->img('images/icons/lock_24x24.png', array('title' => 'Logout', 'alt' => 'Logout')) . '</a>';

			return $Ret;
        }

        $Request = Zend_Controller_Front::getInstance()->getRequest();
        $Controller = $Request->getControllerName();
        $Action = $Request->getActionName();

        if($Controller == 'login' && $Action == 'index')
            return '';

        $LoginURL = $this->view->url(array(
            'controller' => 'login',
            'action' => 'index'
                                     ), 'default');
        $Ret .= '<a href="' . $LoginURL . '">' . $this->view->img('images/icons/unlock_24x24.png', array('title' => 'Login', 'alt' => 'Login')) . '</a>';

		return $Ret;
    }
}

?>