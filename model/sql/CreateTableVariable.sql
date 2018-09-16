CREATE TABLE IF NOT EXISTS `variable` (
	`numero` 	tinyint(1) unsigned NOT NULL,
	`etat` 		varchar(16) NOT NULL,
	`time` 		int(11) unsigned NOT NULL,
	PRIMARY KEY (`numero`,`time`),
	KEY `etat` (`etat`)
) 
ENGINE=MyISAM DEFAULT CHARSET=latin1			
