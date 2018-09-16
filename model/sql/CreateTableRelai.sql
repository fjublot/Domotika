CREATE TABLE IF NOT EXISTS relai (
	numero 		tinyint(1) unsigned NOT NULL,
	carte_id 	tinyint(1) unsigned NOT NULL,
	etat 		char(1) NOT NULL,
	time 		int(11) unsigned NOT NULL,
	PRIMARY KEY (numero,carte_id,time),
	KEY etat (etat)
) 
ENGINE=MyISAM  DEFAULT CHARSET=latin1