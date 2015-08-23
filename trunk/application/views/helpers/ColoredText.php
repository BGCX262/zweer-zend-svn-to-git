<?php

class App_View_Helper_ColoredText extends Zend_View_Helper_Abstract
{
	public function coloredText($Text, $Color)
	{
		$Text = $this->view->escape($Text);
		$Text = '<span style="color: ' . $Color . ';">' . $Text . '</span>';

		return $Text;
	}
}

?>