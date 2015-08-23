-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 06, 2011 at 12:58 PM
-- Server version: 5.5.9
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `noliv`
--

-- --------------------------------------------------------

--
-- Table structure for table `eventi`
--

DROP TABLE IF EXISTS `eventi`;
CREATE TABLE IF NOT EXISTS `eventi` (
  `IDEvento` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `DataInizio` datetime NOT NULL,
  `DataFine` datetime NOT NULL,
  `Titolo` varchar(100) NOT NULL,
  `Descrizione` text,
  `IsGiorno` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Se non è un impegno specifico ma occupa tutto il giorno (quindi ora di inizio e ora di fine sono ininfluenti)',
  `Repeat` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'Ogni quanto si ripete l''evento, in secondi',
  PRIMARY KEY (`IDEvento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `eventi`
--


-- --------------------------------------------------------

--
-- Table structure for table `foto`
--

DROP TABLE IF EXISTS `foto`;
CREATE TABLE IF NOT EXISTS `foto` (
  `IDFoto` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `IDPadre` bigint(20) unsigned NOT NULL COMMENT 'Può essere sia l''ID della pagina a cui l''album appartiene, sia l''ID dell''album a cui la foto appartiene',
  `Titolo` varchar(100) NOT NULL,
  `Descrizione` text NOT NULL,
  `Estensione` varchar(4) NOT NULL DEFAULT '' COMMENT 'Se è uguale a '''', allora il record è un album',
  `Data` datetime NOT NULL,
  `Ratio` decimal(5,2) unsigned NOT NULL DEFAULT '1.00' COMMENT 'Rapporto tra la larghezza e l''altezza. Serve negli allineamenti delle immagini',
  PRIMARY KEY (`IDFoto`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=61 ;

--
-- Dumping data for table `foto`
--

INSERT INTO `foto` (`IDFoto`, `IDPadre`, `Titolo`, `Descrizione`, `Estensione`, `Data`, `Ratio`) VALUES
(3, 1, 'Album di prova', 'breve descrizione!!!', '', '2011-06-18 10:02:18', '1.00');

-- --------------------------------------------------------

--
-- Table structure for table `messaggi`
--

DROP TABLE IF EXISTS `messaggi`;
CREATE TABLE IF NOT EXISTS `messaggi` (
  `IDMessaggio` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `IDPadre` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'Ogni messaggio ha un IDPadre, quindi anche i "padri" (che avranno IDPadre = IDMessaggio)',
  `IDMittente` bigint(20) unsigned NOT NULL,
  `Testo` text NOT NULL,
  `Data` datetime NOT NULL,
  `Letto` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`IDMessaggio`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `messaggi`
--

INSERT INTO `messaggi` (`IDMessaggio`, `IDPadre`, `IDMittente`, `Testo`, `Data`, `Letto`) VALUES
(5, 5, 1, 'Ciao come va?', '2011-05-27 20:53:30', 0),
(6, 5, 1, 'Bene grazie!\r\n\r\nE tu?', '2011-05-27 20:53:46', 0),
(7, 5, 1, 'Anche io bene, grazie mille!', '2011-05-27 20:53:57', 0),
(8, 8, 1, 'Prova prova', '2011-05-27 20:54:18', 0);

-- --------------------------------------------------------

--
-- Table structure for table `messaggi_utenti`
--

DROP TABLE IF EXISTS `messaggi_utenti`;
CREATE TABLE IF NOT EXISTS `messaggi_utenti` (
  `IDMessaggiUtenti` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `IDMessaggio` bigint(20) unsigned NOT NULL,
  `IDUtente` bigint(20) unsigned NOT NULL,
  `Letto` tinyint(1) NOT NULL DEFAULT '0',
  `Data` datetime NOT NULL COMMENT 'Quando l''utente legge il messaggio',
  PRIMARY KEY (`IDMessaggiUtenti`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `messaggi_utenti`
--

INSERT INTO `messaggi_utenti` (`IDMessaggiUtenti`, `IDMessaggio`, `IDUtente`, `Letto`, `Data`) VALUES
(6, 5, 1, 0, '2011-05-27 20:53:30'),
(7, 8, 1, 0, '2011-05-27 20:54:18');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `IDNews` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `IDPagina` bigint(20) unsigned NOT NULL,
  `IDAutore` bigint(20) unsigned NOT NULL,
  `Titolo` varchar(50) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL,
  `Testo` text CHARACTER SET utf8 NOT NULL,
  `Data` datetime NOT NULL,
  PRIMARY KEY (`IDNews`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`IDNews`, `IDPagina`, `IDAutore`, `Titolo`, `Testo`, `Data`) VALUES
(1, 3, 1, 'News di prova', 'Questa è una news, bella nè??', '2011-05-24 19:15:16'),
(2, 3, 1, 'Altra news di prova', 'vediamo se funziona la <b>formattazione</b>', '2011-05-17 19:15:37');

-- --------------------------------------------------------

--
-- Table structure for table `pagine`
--

DROP TABLE IF EXISTS `pagine`;
CREATE TABLE IF NOT EXISTS `pagine` (
  `IDPagina` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `IDPadre` bigint(20) unsigned NOT NULL,
  `Titolo` varchar(50) NOT NULL,
  `Testo` text NOT NULL,
  `URL` varchar(50) NOT NULL,
  `Tipo` enum('pages','news','gallery') NOT NULL DEFAULT 'pages',
  PRIMARY KEY (`IDPagina`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `pagine`
--

INSERT INTO `pagine` (`IDPagina`, `IDPadre`, `Titolo`, `Testo`, `URL`, `Tipo`) VALUES
(1, 0, 'Contatti', 'Qui ci sono tutti i contatti del sito', 'contatti', 'pages'),
(2, 1, 'Cartina', 'Qui c''è la cartina per raggiungerci!!!', 'cartina', 'pages'),
(3, 0, 'News', 'Qui ci sono tutte le news che bisogna tenere sott''occhio', 'news', 'news');

-- --------------------------------------------------------

--
-- Table structure for table `utenti`
--

DROP TABLE IF EXISTS `utenti`;
CREATE TABLE IF NOT EXISTS `utenti` (
  `IDUtente` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Nome` varchar(30) NOT NULL,
  `Cognome` varchar(30) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` char(40) CHARACTER SET utf8 NOT NULL,
  `Salt` char(40) NOT NULL,
  `DataCreazione` datetime NOT NULL,
  PRIMARY KEY (`IDUtente`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `utenti`
--

INSERT INTO `utenti` (`IDUtente`, `Nome`, `Cognome`, `Email`, `Password`, `Salt`, `DataCreazione`) VALUES
(1, 'Niccolò', 'Olivieri', 'flicofloc@gmail.com', '25a6941f5ffbcfcb29218eb3842eb92fdbc30322', '1b6453892473a467d07372d45eb05abc2031647a', '2011-05-25 19:56:13');
