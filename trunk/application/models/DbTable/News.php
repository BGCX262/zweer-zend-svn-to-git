<?php

class App_Model_DbTable_News extends Zend_Db_Table_Abstract
{
    protected $_name = 'news';
    protected $_primary = 'IDNews';

	protected $_rewrite = array('Titolo' => 'Title',
								'Testo' => 'Text',
								'Data' => 'Date',
								'IDPagina' => 'IDPage',
								'IDAutore' => 'IDAuthor');

	public function addNews($Title, $Text, $IDPage)
	{
		$Data = array('Titolo' => $Title, 'Testo' => $Text, 'IDPagina' => $IDPage, 'IDAutore' => '0', 'Data' => new Zend_Db_Expr('NOW()'));

		$this->insert($Data);
	}

	public function updateNews($IDNews, $Title, $Text, $IDPage)
	{
		$Data = array('Titolo' => $Title, 'Testo' => $Text, 'IDPagina' => $IDPage);

		$this->update($Data, "IDNews = '$IDNews'");
	}

	public function deleteNews($IDNews)
	{
		$this->delete("IDNews = '$IDNews'");
	}

	public function getNews($IDNews)
	{
		$IDNews = intval($IDNews);
		$News = $this->fetchRow("IDNews = '$IDNews'");

		if(!$News)
			throw new Exception("Could not find news $IDNews");

		return Zwe_Db_Table_Row::rewrite($News, $this->_rewrite);
	}

    public function getNewsFromPage($IDPage)
    {
        $IDPage = (int) $IDPage;
        return $this->fetchAll("IDPagina = '$IDPage'");
    }
}

