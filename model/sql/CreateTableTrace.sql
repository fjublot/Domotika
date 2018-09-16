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
