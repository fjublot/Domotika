-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Mar 08 Mars 2016 à 00:58
-- Version du serveur :  5.5.44-0+deb8u1
-- Version de PHP :  5.6.17-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `raspisms`
--

-- --------------------------------------------------------

--
-- Structure de la table `alert`
--

CREATE TABLE IF NOT EXISTS `alert` (
`id` int(1) unsigned NOT NULL,
  `carte_id` int(1) NOT NULL,
  `trace_id` int(11) DEFAULT NULL,
  `message` varchar(2048) NOT NULL,
  `class` varchar(255) NOT NULL,
  `no` int(11) NOT NULL,
  `timeutc` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `timezone` varchar(255) NOT NULL,
  `microtime` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Structure de la table `an`
--

CREATE TABLE IF NOT EXISTS `an` (
  `numero` tinyint(1) unsigned NOT NULL,
  `carte_id` tinyint(1) unsigned NOT NULL,
  `etat` decimal(10,8) NOT NULL,
  `time` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `btn`
--

CREATE TABLE IF NOT EXISTS `btn` (
  `numero` tinyint(1) unsigned NOT NULL,
  `carte_id` tinyint(1) unsigned NOT NULL,
  `etat` char(2) NOT NULL,
  `time` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `cnt`
--

CREATE TABLE IF NOT EXISTS `cnt` (
  `numero` tinyint(1) unsigned NOT NULL,
  `carte_id` tinyint(1) unsigned NOT NULL,
  `etat` int(1) unsigned NOT NULL,
  `time` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
-- --------------------------------------------------------

--
-- Structure de la table `relai`
--

CREATE TABLE IF NOT EXISTS `relai` (
  `numero` tinyint(1) unsigned NOT NULL,
  `carte_id` tinyint(1) unsigned NOT NULL,
  `etat` char(1) NOT NULL,
  `time` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
--
-- Structure de la table `trace`
--

CREATE TABLE IF NOT EXISTS `trace` (
`id` int(1) unsigned NOT NULL,
  `user_id` bigint(1) unsigned NOT NULL,
  `type` varchar(20) NOT NULL,
  `ipfrom` int(1) NOT NULL,
  `texte` varchar(255) NOT NULL,
  `timeutc` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `timezone` varchar(255) DEFAULT NULL,
  `microtime` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Structure de la table `vartxt`
--

CREATE TABLE IF NOT EXISTS `vartxt` (
  `numero` tinyint(1) unsigned NOT NULL,
  `etat` varchar(16) NOT NULL,
  `time` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `alert`
--
ALTER TABLE `alert`
 ADD PRIMARY KEY (`id`), ADD KEY `carte_id` (`carte_id`), ADD KEY `trace_id` (`trace_id`);

--
-- Index pour la table `an`
--
ALTER TABLE `an`
 ADD PRIMARY KEY (`numero`,`carte_id`,`time`), ADD KEY `etat` (`etat`);

--
-- Index pour la table `btn`
--
ALTER TABLE `btn`
 ADD PRIMARY KEY (`numero`,`carte_id`,`time`), ADD KEY `etat` (`etat`);

--
-- Index pour la table `cnt`
--
ALTER TABLE `cnt`
 ADD PRIMARY KEY (`numero`,`carte_id`,`time`), ADD KEY `etat` (`etat`);

--
-- Index pour la table `relai`
--
ALTER TABLE `relai`
 ADD PRIMARY KEY (`numero`,`carte_id`,`time`), ADD KEY `etat` (`etat`);

--
-- Index pour la table `trace`
--
ALTER TABLE `trace`
 ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`), ADD KEY `type` (`type`), ADD KEY `from` (`ipfrom`), ADD KEY `time` (`timeutc`);


--
-- Index pour la table `vartxt`
--
ALTER TABLE `vartxt`
 ADD PRIMARY KEY (`numero`,`time`), ADD KEY `etat` (`etat`);

