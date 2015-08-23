<?php

class Zwe_Db_Table_Row extends Zend_Db_Table_Row
{
	public static function rewrite(Zend_Db_Table_Row_Abstract $Row, array $Rewrite = null)
	{
		$Array = $Row->toArray();
		$ArrayRet = array();

		foreach($Array as $Key => $Value)
		{
			if(isset($Rewrite[$Key]))
				$ArrayRet[$Rewrite[$Key]] = $Value;
			else
				$ArrayRet[$Key] = $Value;
		}

		return $ArrayRet;
	}
}

?>