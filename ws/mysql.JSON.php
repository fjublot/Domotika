<?php
	header('Content-Type: application/json; charset=utf-8');
	//CONNEXION A LA BASE DE DONNEES
	$host     = $GLOBALS["config"]->general->mysql_host ;
	$dbname   = $GLOBALS["config"]->general->mysql_db ;
	$user     = $GLOBALS["config"]->general->mysql_user ;
	$password = $GLOBALS["config"]->general->mysql_password ;
	$port     = $GLOBALS["config"]->general->mysql_port ;

	//switch ($_REQUEST["action"]) {
		/*'connectmysql' ||*/ 
		if (($_REQUEST["action"] == 'connectmysql') ||($_REQUEST["action"] == 'connectdatabase')) {
		//if ($_REQUEST["action"]) {
				try {
				$error = false;
				// On se connecte à MySQL
				$mysql = new PDO('mysql:host=' . $host . ';port=' . $port, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

				//$bdd->exec("SET CHARACTER SET utf8");
				
			}
			catch(PDOException $e) {
				// En cas d'erreur, on affiche un message et on arrête tout
				$error = true;
			}
		
			$result[] = array(
				'action' => $_REQUEST["action"],
				'valid' => $error ? false : true,
				'message' => $error ? fn_GetTranslation('unable_to_connect_db', $host) : fn_GetTranslation('connect_db', $host)
			);
		}
		
		
		if ($_REQUEST["action"] == 'connectdatabase') {
			// Création de la base si elle est inexistante
			$requete = "CREATE DATABASE IF NOT EXISTS " . $dbname . " DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci";
			$error = false;
			$mysql = new PDO('mysql:host=' . $host . ';port=' . $port, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	
			try {
				$mysql->exec ($requete);
			} 
			catch (PDOException $e) {
				$error = true;
			} 
			
			$result[] = array(
				'action'  => $_REQUEST["action"],
				'valid'   => $error ? false : true,
				'message' => $error ? fn_GetTranslation('unable_to_create_db', $dbname) : fn_GetTranslation('create_db', $dbname)
			);
		}
		
		if ($_REQUEST["action"] == 'createtablerelai') {
			include($GLOBALS["mvc_path"].'connbdd.php');
			//On va appeler un modèle, et l'initialiser
			$db = new database($bdd);
			$retour=$db->createtablerelai();
			$result[] = array(
				'action'     => $_REQUEST["action"],
				'query'      => $retour["query"],
				'valid'      => $retour["valid"]?true:false,
				'pdoerror'   => $retour["pdoerror"],
				'pdomessage' => $retour["pdomessage"],
				'message'    => $retour["valid"] ? fn_GetTranslation('create_table', 'relai') : fn_GetTranslation('create_table_error', 'relai', $retour["pdoerror"])
			);
		}

		if ($_REQUEST["action"] == 'createtablean') {
			include($GLOBALS["mvc_path"].'connbdd.php');
			//On va appeler un modèle, et l'initialiser
			$db = new database($bdd);
			$retour=$db->createtablean();
			$result[] = array(
				'action'     => $_REQUEST["action"],
				'query'      => $retour["query"],
				'valid'      => $retour["valid"]?true:false,
				'pdoerror'   => $retour["pdoerror"],
				'pdomessage' => $retour["pdomessage"],
				'message'    => $retour["valid"] ? fn_GetTranslation('create_table', 'an') : fn_GetTranslation('create_table_error', 'an', $retour["pdoerror"])
			);
		}

		if ($_REQUEST["action"] == 'createtablebtn') {
			include($GLOBALS["mvc_path"].'connbdd.php');
			//On va appeler un modèle, et l'initialiser
			$db = new database($bdd);
			$retour=$db->createtablebtn();
			$result[] = array(
				'action'     => $_REQUEST["action"],
				'query'      => $retour["query"],
				'valid'      => $retour["valid"]?true:false,
				'pdoerror'   => $retour["pdoerror"],
				'pdomessage' => $retour["pdomessage"],
				'message'    => $retour["valid"] ? fn_GetTranslation('create_table', 'btn') : fn_GetTranslation('create_table_error', 'btn', $retour["pdoerror"])
			);
		}

		if ($_REQUEST["action"] == 'createtablecnt') {
			include($GLOBALS["mvc_path"].'connbdd.php');
			//On va appeler un modèle, et l'initialiser
			$db = new database($bdd);
			$retour=$db->createtablecnt();
			$result[] = array(
				'action'     => $_REQUEST["action"],
				'query'      => $retour["query"],
				'valid'      => $retour["valid"]?true:false,
				'pdoerror'   => $retour["pdoerror"],
				'pdomessage' => $retour["pdomessage"],
				'message'    => $retour["valid"] ? fn_GetTranslation('create_table', 'cnt') : fn_GetTranslation('create_table_error', 'cnt', $retour["pdoerror"])
			);
		}
		
		if ($_REQUEST["action"] == 'createtablevariable') {
			include($GLOBALS["mvc_path"].'connbdd.php');
			//On va appeler un modèle, et l'initialiser
			$db = new database($bdd);
			$retour=$db->createtablevariable();
			$result[] = array(
				'action'     => $_REQUEST["action"],
				'query'      => $retour["query"],
				'valid'      => $retour["valid"]?true:false,
				'pdoerror'   => $retour["pdoerror"],
				'pdomessage' => $retour["pdomessage"],
				'message'    => $retour["valid"] ? fn_GetTranslation('create_table', 'variable') : fn_GetTranslation('create_table_error', 'variable', $retour["pdoerror"])
			);
		}

		if ($_REQUEST["action"] == 'createtablevartxt') {
			include($GLOBALS["mvc_path"].'connbdd.php');
			//On va appeler un modèle, et l'initialiser
			$db = new database($bdd);
			$retour=$db->createtablevartxt();
			$result[] = array(
				'action'     => $_REQUEST["action"],
				'query'      => $retour["query"],
				'valid'      => $retour["valid"]?true:false,
				'pdoerror'   => $retour["pdoerror"],
				'pdomessage' => $retour["pdomessage"],
				'message'    => $retour["valid"] ? fn_GetTranslation('create_table', 'vartxt') : fn_GetTranslation('create_table_error', 'vartxt', $retour["pdoerror"])
			);
		}

		if ($_REQUEST["action"] == 'createtabletrace') {
			include($GLOBALS["mvc_path"].'connbdd.php');
			//On va appeler un modèle, et l'initialiser
			$db = new database($bdd);
			$retour=$db->createtabletrace();
			$result[] = array(
				'action'     => $_REQUEST["action"],
				'query'      => $retour["query"],
				'valid'      => $retour["valid"]?true:false,
				'pdoerror'   => $retour["pdoerror"],
				'pdomessage' => $retour["pdomessage"],
				'message'    => $retour["valid"] ? fn_GetTranslation('create_table', 'trace') : fn_GetTranslation('create_table_error', 'trace', $retour["pdoerror"])
			);
		}

		if ($_REQUEST["action"] == 'droptableterminal') {
			include($GLOBALS["mvc_path"].'connbdd.php');
			//On va appeler un modèle, et l'initialiser
			$db = new database($bdd);
			$retour=$db->droptable('terminal');
			$result[] = array(
				'action'     => $_REQUEST["action"],
				'query'      => $retour["query"],
				'valid'      => $retour["valid"]?true:false,
				'pdoerror'   => $retour["pdoerror"],
				'pdomessage' => $retour["pdomessage"],
				'message'    => $retour["valid"] ? fn_GetTranslation('drop_table', 'terminal') : fn_GetTranslation('drop_table_error', 'terminal', $retour["pdoerror"])
			);
		}


		if ($_REQUEST["action"] == 'altertabletracedropanneemoisjour') {
			include($GLOBALS["mvc_path"].'connbdd.php');
			//On va appeler un modèle, et l'initialiser
			$db = new database($bdd);
			$retour=$db->altertabletracedropanneemoisjour();
			$result[] = array(
				'action'     => $_REQUEST["action"],
				'query'      => $retour["query"],
				'valid'      => $retour["valid"]?true:false,
				'pdoerror'   => $retour["pdoerror"],
				'pdomessage' => $retour["pdomessage"],
				'message'    => $retour["valid"] ? fn_GetTranslation('alter_table', 'trace', 'drop annee_mois_jour') : fn_GetTranslation('alter_table_error', 'trace', $retour["pdoerror"])
			);
		}

		if ($_REQUEST["action"] == 'altertabletraceaddtimezone') {
			include($GLOBALS["mvc_path"].'connbdd.php');
			//On va appeler un modèle, et l'initialiser
			$db = new database($bdd);
			$retour=$db->altertabletraceaddtimezone();
			$result[] = array(
				'action'     => $_REQUEST["action"],
				'query'      => $retour["query"],
				'valid'      => $retour["valid"]?true:false,
				'pdoerror'   => $retour["pdoerror"],
				'pdomessage' => $retour["pdomessage"],
				'message'    => $retour["valid"] ? fn_GetTranslation('alter_table', 'trace', 'add timezone') : fn_GetTranslation('alter_table_error', 'trace', $retour["pdoerror"])
			);
		}

		if ($_REQUEST["action"] == 'altertabletraceaddmicrotime') {
			include($GLOBALS["mvc_path"].'connbdd.php');
			//On va appeler un modèle, et l'initialiser
			$db = new database($bdd);
			$retour=$db->altertabletraceaddmicrotime();
			$result[] = array(
				'action'     => $_REQUEST["action"],
				'query'      => $retour["query"],
				'valid'      => $retour["valid"]?true:false,
				'pdoerror'   => $retour["pdoerror"],
				'pdomessage' => $retour["pdomessage"],
				'message'    => $retour["valid"] ? fn_GetTranslation('alter_table', 'trace', 'add microtime') : fn_GetTranslation('alter_table_error', 'trace', $retour["pdoerror"])
			);
		}

		if ($_REQUEST["action"] == 'altertableanetat') {
			include($GLOBALS["mvc_path"].'connbdd.php');
			//On va appeler un modèle, et l'initialiser
			$db = new database($bdd);
			$retour=$db->fielddecimalanetat();
			$result[] = array(
				'action'     => $_REQUEST["action"],
				'query'      => $retour["query"],
				'valid'      => $retour["valid"]?true:false,
				'pdoerror'   => $retour["pdoerror"],
				'pdomessage' => $retour["pdomessage"],
				'message'    => $retour["valid"] ? fn_GetTranslation('alter_table', 'an', 'decimal etat ('.$retour["pdoerror"].')') : fn_GetTranslation('alter_table_error', 'an', $retour["pdoerror"])
			);
		}

		if ($_REQUEST["action"] == 'altertablecntetat') {
			include($GLOBALS["mvc_path"].'connbdd.php');
			//On va appeler un modèle, et l'initialiser
			$db = new database($bdd);
			$retour=$db->fielddecimalcntetat();
			$result[] = array(
				'action'     => $_REQUEST["action"],
				'query'      => $retour["query"],
				'valid'      => $retour["valid"]?true:false,
				'pdoerror'   => $retour["pdoerror"],
				'pdomessage' => $retour["pdomessage"],
				'message'    => $retour["valid"] ? fn_GetTranslation('alter_table', 'cnt', 'decimal etat ('.$retour["pdoerror"].')') : fn_GetTranslation('alter_table_error', 'cnt', $retour["pdoerror"])
			);
		}

		if ($_REQUEST["action"] == 'altertablevariableetat') {
			include($GLOBALS["mvc_path"].'connbdd.php');
			//On va appeler un modèle, et l'initialiser
			$db = new database($bdd);
			$retour=$db->fielddecimalvariableetat();
			$result[] = array(
				'action'     => $_REQUEST["action"],
				'query'      => $retour["query"],
				'valid'      => $retour["valid"]?true:false,
				'pdoerror'   => $retour["pdoerror"],
				'pdomessage' => $retour["pdomessage"],
				'message'    => $retour["valid"] ? fn_GetTranslation('alter_table', 'variable', 'decimal etat ('.$retour["pdoerror"].')') : fn_GetTranslation('alter_table_error', 'variable', $retour["pdoerror"])
			);
		}

		if ($_REQUEST["action"] == 'altertablebtnetat') {
			include($GLOBALS["mvc_path"].'connbdd.php');
			//On va appeler un modèle, et l'initialiser
			$db = new database($bdd);
			$retour=$db->fielddecimalbtnetat();
			$result[] = array(
				'action'     => $_REQUEST["action"],
				'query'      => $retour["query"],
				'valid'      => $retour["valid"]?true:false,
				'pdoerror'   => $retour["pdoerror"],
				'pdomessage' => $retour["pdomessage"],
				'message'    => $retour["valid"] ? fn_GetTranslation('alter_table', 'btn', 'decimal etat ('.$retour["pdoerror"].')') : fn_GetTranslation('alter_table_error', 'btn', $retour["pdoerror"])
			);
		}

		if ($_REQUEST["action"] == 'altertabletraceetat') {
			include($GLOBALS["mvc_path"].'connbdd.php');
			//On va appeler un modèle, et l'initialiser
			$db = new database($bdd);
			$retour=$db->fielddecimaltraceetat();
			$result[] = array(
				'action'     => $_REQUEST["action"],
				'query'      => $retour["query"],
				'valid'      => $retour["valid"]?true:false,
				'pdoerror'   => $retour["pdoerror"],
				'pdomessage' => $retour["pdomessage"],
				'message'    => $retour["valid"] ? fn_GetTranslation('alter_table', 'trace', 'decimal etat ('.$retour["pdoerror"].')') : fn_GetTranslation('alter_table_error', 'trace', $retour["pdoerror"])
			);
		}
		
		if (count($result)==0) {
			$result[] = array(
				'action'     => $_REQUEST["action"],
				'query'      => null,
				'valid'      => false,
				'pdoerror'   => 0,
				'pdomessage' => null,
				'message'    => fn_GetTranslation('ActionNotExist')
			);
		}
		/*



			$db->forceengine;
			$db->purgeetatvide("btn");
			$db->purgeetatvide("relai");

			
			//Efface les valeurs en double
			foreach( array('cnt', 'an', 'btn', 'relai', 'variable', 'vartxt') as $table) {
				$db->purgedouble($table);
				$db->optimizetable($table);
			}

			$db->optimizetable("trace");
		*/
		
		//else {
		// Dans tous les autres cas
		//	$result[] = array(
		//		'action' => 'none',
		//		'valid' => false,
		//		'message' => fn_GetTranslation('none')
		//	);
		//}
	//}
	
	// Encodage du tableau en json			
	echo json_encode($result);

?>