-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mar 26 Janvier 2016 à 18:25
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `domowamp`
--

-- --------------------------------------------------------

--
-- Structure de la table `alert`
--

CREATE TABLE IF NOT EXISTS `alert` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `carte_id` int(1) NOT NULL,
  `trace_id` int(11) DEFAULT NULL,
  `message` varchar(2048) NOT NULL,
  `class` varchar(255) NOT NULL,
  `no` int(11) NOT NULL,
  `timeutc` timestamp NOT NULL,
  `timezone` varchar(255) NOT NULL,
  `microtime` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `carte_id` (`carte_id`),
  KEY `trace_id` (`trace_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `an`
--

CREATE TABLE IF NOT EXISTS `an` (
  `numero` tinyint(1) unsigned NOT NULL,
  `carte_id` tinyint(1) unsigned NOT NULL,
  `etat` decimal(10,8) NOT NULL,
  `time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`numero`,`carte_id`,`time`),
  KEY `etat` (`etat`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `btn`
--

CREATE TABLE IF NOT EXISTS `btn` (
  `numero` tinyint(1) unsigned NOT NULL,
  `carte_id` tinyint(1) unsigned NOT NULL,
  `etat` char(2) NOT NULL,
  `time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`numero`,`carte_id`,`time`),
  KEY `etat` (`etat`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `cnt`
--

CREATE TABLE IF NOT EXISTS `cnt` (
  `numero` tinyint(1) unsigned NOT NULL,
  `carte_id` tinyint(1) unsigned NOT NULL,
  `etat` int(1) unsigned NOT NULL,
  `time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`numero`,`carte_id`,`time`),
  KEY `etat` (`etat`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `trace`
--

CREATE TABLE IF NOT EXISTS `trace` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` tinyint(1) unsigned NOT NULL,
  `type` varchar(10) NOT NULL,
  `ipfrom` int(1) NOT NULL,
  `texte` varchar(255) NOT NULL,
  `timeutc` timestamp NOT NULL,
  `timezone` varchar(255) DEFAULT NULL,
  `microtime` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `type` (`type`),
  KEY `from` (`ipfrom`),
  KEY `time` (`timeutc`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `variable`
--

CREATE TABLE IF NOT EXISTS `variable` (
  `numero` tinyint(1) unsigned NOT NULL,
  `etat` int(1) unsigned NOT NULL,
  `time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`numero`,`time`),
  KEY `etat` (`etat`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `vartxt`
--

CREATE TABLE IF NOT EXISTS `vartxt` (
  `numero` tinyint(1) unsigned NOT NULL,
  `etat` varchar(16) NOT NULL,
  `time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`numero`,`time`),
  KEY `etat` (`etat`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
