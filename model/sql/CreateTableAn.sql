CREATE TABLE IF NOT EXISTS `an` (
	`numero` 	tinyint(1) unsigned NOT NULL,
	`carte_id` 	tinyint(1) unsigned NOT NULL,
	`etat` 		decimal(10,8) NOT NULL,
	`time` 		int(11) unsigned NOT NULL,
	PRIMARY KEY (`numero`,`carte_id`,`time`),
	KEY `etat` (`etat`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1