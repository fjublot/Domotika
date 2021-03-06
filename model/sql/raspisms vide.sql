-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Jeu 24 Mars 2016 à 20:24
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
-- Structure de la table `commands`
--

CREATE TABLE IF NOT EXISTS `commands` (
`id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `script` varchar(100) NOT NULL,
  `admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
`id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
`id` int(11) NOT NULL,
  `type` varchar(25) NOT NULL,
  `at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `text` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
`id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `groups_contacts`
--

CREATE TABLE IF NOT EXISTS `groups_contacts` (
`id` int(11) NOT NULL,
  `id_group` int(11) NOT NULL,
  `id_contact` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `receiveds`
--

CREATE TABLE IF NOT EXISTS `receiveds` (
`id` int(11) NOT NULL,
  `at` datetime NOT NULL,
  `send_by` varchar(20) NOT NULL,
  `content` varchar(1000) NOT NULL,
  `is_command` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `scheduleds`
--

CREATE TABLE IF NOT EXISTS `scheduleds` (
`id` int(11) NOT NULL,
  `at` datetime NOT NULL,
  `content` varchar(1000) NOT NULL,
  `flash` tinyint(1) NOT NULL DEFAULT '0',
  `progress` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `scheduleds_contacts`
--

CREATE TABLE IF NOT EXISTS `scheduleds_contacts` (
`id` int(11) NOT NULL,
  `id_scheduled` int(11) NOT NULL,
  `id_contact` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `scheduleds_groups`
--

CREATE TABLE IF NOT EXISTS `scheduleds_groups` (
`id` int(11) NOT NULL,
  `id_scheduled` int(11) NOT NULL,
  `id_group` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `scheduleds_numbers`
--

CREATE TABLE IF NOT EXISTS `scheduleds_numbers` (
`id` int(11) NOT NULL,
  `id_scheduled` int(11) NOT NULL,
  `number` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `sendeds`
--

CREATE TABLE IF NOT EXISTS `sendeds` (
`id` int(11) NOT NULL,
  `at` datetime NOT NULL,
  `target` varchar(20) NOT NULL,
  `content` varchar(1000) NOT NULL,
  `before_delivered` int(11) NOT NULL,
  `delivered` tinyint(1) NOT NULL DEFAULT '0',
  `failed` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
`id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` varchar(1000) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `sms_stop`
--

CREATE TABLE IF NOT EXISTS `sms_stop` (
`id` int(11) NOT NULL,
  `number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `transfers`
--

CREATE TABLE IF NOT EXISTS `transfers` (
`id` int(11) NOT NULL,
  `id_received` int(11) NOT NULL,
  `progress` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `transfer` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `commands`
--
ALTER TABLE `commands`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- Index pour la table `contacts`
--
ALTER TABLE `contacts`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- Index pour la table `events`
--
ALTER TABLE `events`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `groups`
--
ALTER TABLE `groups`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- Index pour la table `groups_contacts`
--
ALTER TABLE `groups_contacts`
 ADD PRIMARY KEY (`id`), ADD KEY `id_group` (`id_group`), ADD KEY `id_contact` (`id_contact`);

--
-- Index pour la table `receiveds`
--
ALTER TABLE `receiveds`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `scheduleds`
--
ALTER TABLE `scheduleds`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `scheduleds_contacts`
--
ALTER TABLE `scheduleds_contacts`
 ADD PRIMARY KEY (`id`), ADD KEY `id_scheduled` (`id_scheduled`), ADD KEY `id_contact` (`id_contact`);

--
-- Index pour la table `scheduleds_groups`
--
ALTER TABLE `scheduleds_groups`
 ADD PRIMARY KEY (`id`), ADD KEY `id_scheduled` (`id_scheduled`), ADD KEY `id_group` (`id_group`);

--
-- Index pour la table `scheduleds_numbers`
--
ALTER TABLE `scheduleds_numbers`
 ADD PRIMARY KEY (`id`), ADD KEY `id_scheduled` (`id_scheduled`);

--
-- Index pour la table `sendeds`
--
ALTER TABLE `sendeds`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `settings`
--
ALTER TABLE `settings`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- Index pour la table `sms_stop`
--
ALTER TABLE `sms_stop`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `number` (`number`);

--
-- Index pour la table `transfers`
--
ALTER TABLE `transfers`
 ADD PRIMARY KEY (`id`), ADD KEY `id_received` (`id_received`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `commands`
--
ALTER TABLE `commands`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `contacts`
--
ALTER TABLE `contacts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `events`
--
ALTER TABLE `events`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `groups`
--
ALTER TABLE `groups`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `groups_contacts`
--
ALTER TABLE `groups_contacts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `receiveds`
--
ALTER TABLE `receiveds`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `scheduleds`
--
ALTER TABLE `scheduleds`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT pour la table `scheduleds_contacts`
--
ALTER TABLE `scheduleds_contacts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `scheduleds_groups`
--
ALTER TABLE `scheduleds_groups`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `scheduleds_numbers`
--
ALTER TABLE `scheduleds_numbers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT pour la table `sendeds`
--
ALTER TABLE `sendeds`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT pour la table `settings`
--
ALTER TABLE `settings`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `sms_stop`
--
ALTER TABLE `sms_stop`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `transfers`
--
ALTER TABLE `transfers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `groups_contacts`
--
ALTER TABLE `groups_contacts`
ADD CONSTRAINT `groups_contacts_ibfk_1` FOREIGN KEY (`id_group`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `groups_contacts_ibfk_2` FOREIGN KEY (`id_contact`) REFERENCES `contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `scheduleds_contacts`
--
ALTER TABLE `scheduleds_contacts`
ADD CONSTRAINT `scheduleds_contacts_ibfk_1` FOREIGN KEY (`id_scheduled`) REFERENCES `scheduleds` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `scheduleds_contacts_ibfk_2` FOREIGN KEY (`id_contact`) REFERENCES `contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `scheduleds_groups`
--
ALTER TABLE `scheduleds_groups`
ADD CONSTRAINT `scheduleds_groups_ibfk_1` FOREIGN KEY (`id_scheduled`) REFERENCES `scheduleds` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `scheduleds_groups_ibfk_2` FOREIGN KEY (`id_group`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `scheduleds_numbers`
--
ALTER TABLE `scheduleds_numbers`
ADD CONSTRAINT `scheduleds_numbers_ibfk_1` FOREIGN KEY (`id_scheduled`) REFERENCES `scheduleds` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `transfers`
--
ALTER TABLE `transfers`
ADD CONSTRAINT `transfers_ibfk_1` FOREIGN KEY (`id_received`) REFERENCES `receiveds` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
