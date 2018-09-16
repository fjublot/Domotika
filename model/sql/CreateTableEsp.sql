CREATE TABLE IF NOT EXISTS `esp` (
	`numero` 	tinyint(1) unsigned NOT NULL,
	`etat` 		decimal(10,8) NOT NULL,
	`time` 		int(11) unsigned NOT NULL,
	PRIMARY KEY (`numero`,`time`),
	KEY `etat` (`etat`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1