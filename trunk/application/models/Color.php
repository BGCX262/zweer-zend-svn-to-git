<?php

class App_Model_Color
{
	public function getRandomColor()
	{
		$R = mt_rand(0, 255);
		$G = mt_rand(0, 255);
		$B = mt_rand(0, 255);

		return $this->getHtmlColor($R, $G, $B);
	}

	public function getHtmlColor($R, $G, $B)
	{
		return '#' . $this->_getHex($R) . $this->_getHex($G) . $this->_getHex($B);
	}

	protected function _getHex($Number, $Digits = 2)
	{
		return substr(str_repeat('0', $Digits) . dechex($Number), -$Digits);
	}
}

?>