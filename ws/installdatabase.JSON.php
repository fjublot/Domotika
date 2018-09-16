<?php
	header('Content-Type: application/json; charset=utf-8');
	//CONNEXION A LA BASE DE DONNEES	
	$result['valid']=true; // Bon par défaut
	
	try {
		$error = false;
		$pdomessage="";
		$pdoerror=0;
		// On se connecte à MySQL
		$mysql = new PDO('mysql:host=' . $mysql_host . ';port=' . $mysql_port, $mysql_user, $mysql_password, array(PDO::ATTR_PERSISTENT => TRUE));
		$mysql->exec("SET CHARACTER SET utf8");
	}
	catch(PDOException $e) {
		// En cas d'erreur, on affiche un message et on arrête tout
		$error = true;
		$pdomessage = $e->getMessage();
		$pdoerror = $e->getCode();
	}
	
	$result['actions'][] = array(
		'valid' => $error ? false : true,
		'pdoerror'   => $pdoerror,
		'pdomessage' => $pdomessage,
		'string' => 'mysql:host=' . $mysql_host . ';port=' . $mysql_port.','. $mysql_user.','. $mysql_password,
		'message' =>$error ? fn_GetTranslation('unable_to_connect_db', $mysql_host, $pdomessage) : fn_GetTranslation('connect_db', $mysql_host)
	);

	if ($error==true) $result['valid']=false;
	
	if ($error==false) {
		// Création de la base si elle est inexistante
		$requete = "CREATE DATABASE IF NOT EXISTS " . $mysql_db . " DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci";
		$error = false;
		$pdoerror = 0;
		$pdomessage= "";
		try {
			$mysql->exec ($requete);
		} 
		catch (PDOException $e) {
			$error = true;
			$pdomessage = $e->getMessage();
			$pdoerror = $e->getCode();
		} 
		
		$result['actions'][] = array(
			'query'      => $requete,
			'valid'   => $error ? false : true,
			'pdoerror'   => $pdoerror,
			'pdomessage' => $pdomessage,
			'message' =>$error ? ucfirst(fn_GetTranslation('unable_to_create_db', $mysql_db, $pdoerror)) : ucfirst(fn_GetTranslation('create_dbname', $mysql_db))
		);

	try {
		// On se connecte à MySQL
		$bdd = new PDO('mysql:host=' . $mysql_host . ';port=' . $mysql_port . ';dbname=' . $mysql_db, $mysql_user, $mysql_password, array(PDO::ATTR_PERSISTENT => TRUE));
		$bdd->exec("SET CHARACTER SET utf8");
	}
	catch(Exception $e) {
		// En cas d'erreur, on affiche un message et on arrête tout
		die ('Erreur : '.$e->getMessage());
	}



		//On va appeler un modèle, et l'initialiser
		$db = new database($bdd);
		$retour=$db->createtablerelai();
		$result['actions'][] = array(
			'query'      => $retour["query"],
			'valid'      => $retour["valid"]?true:false,
			'pdoerror'   => $retour["pdoerror"],
			'pdomessage' => $retour["pdomessage"],
		'message' =>$retour["valid"] ? fn_GetTranslation('create_table', 'relai') : fn_GetTranslation('create_table_error', 'relai', $retour["pdoerror"])
		);

		$retour=$db->createtablean();
		$result['actions'][] = array(
			'query'      => $retour["query"],
			'valid'      => $retour["valid"]?true:false,
			'pdoerror'   => $retour["pdoerror"],
			'pdomessage' => $retour["pdomessage"],
		'message' =>$retour["valid"] ? fn_GetTranslation('create_table', 'an') : fn_GetTranslation('create_table_error', 'an', $retour["pdoerror"])
		);
		if ($retour["valid"]==false) $result['valid']=false;

		$retour=$db->createtablebtn();
		$result['actions'][] = array(
			'query'      => $retour["query"],
			'valid'      => $retour["valid"]?true:false,
			'pdoerror'   => $retour["pdoerror"],
			'pdomessage' => $retour["pdomessage"],
		'message' =>$retour["valid"] ? fn_GetTranslation('create_table', 'btn') : fn_GetTranslation('create_table_error', 'btn', $retour["pdoerror"])
		);

		if ($retour["valid"]==false) $result['valid']=false;

		$retour=$db->createtablecnt();
		$result['actions'][] = array(
			'query'      => $retour["query"],
			'valid'      => $retour["valid"]?true:false,
			'pdoerror'   => $retour["pdoerror"],
			'pdomessage' => $retour["pdomessage"],
		'message' =>$retour["valid"] ? fn_GetTranslation('create_table', 'cnt') : fn_GetTranslation('create_table_error', 'cnt', $retour["pdoerror"])
		);
		if ($retour["valid"]==false) $result['valid']=false;

		$retour=$db->createtablevariable();
		$result['actions'][] = array(
			'query'      => $retour["query"],
			'valid'      => $retour["valid"]?true:false,
			'pdoerror'   => $retour["pdoerror"],
			'pdomessage' => $retour["pdomessage"],
			'message'    => $retour["valid"] ? fn_GetTranslation('create_table', 'variable') : fn_GetTranslation('create_table_error', 'variable', $retour["pdoerror"])
		);
		if ($retour["valid"]==false) $result['valid']=false;

		$retour=$db->createtablevartxt();
		$result['actions'][] = array(
			'query'      => $retour["query"],
			'valid'      => $retour["valid"]?true:false,
			'pdoerror'   => $retour["pdoerror"],
			'pdomessage' => $retour["pdomessage"],
		'message' =>$retour["valid"] ? fn_GetTranslation('create_table', 'vartxt') : fn_GetTranslation('create_table_error', 'vartxt', $retour["pdoerror"])
		);
		if ($retour["valid"]==false) $result['valid']=false;

		$retour=$db->createtabletrace();
		$result['actions'][] = array(
			'query'      => $retour["query"],
			'valid'      => $retour["valid"]?true:false,
			'pdoerror'   => $retour["pdoerror"],
			'pdomessage' => $retour["pdomessage"],
		'message' =>$retour["valid"] ? fn_GetTranslation('create_table', 'trace') : fn_GetTranslation('create_table_error', 'trace', $retour["pdoerror"])
		);
		if ($retour["valid"]==false) $result['valid']=false;
	}
	echo json_encode($result);

?>