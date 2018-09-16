<?php
	$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
	$result['time 0'] = $time;

	header('Content-Type: application/json; charset=utf-8');
	$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
	$result['time debut'] = $time;
	
	//Si des utilisateurs existent et que le login est transmis
	if ( isset($GLOBALS["config"]->users) && isset($_REQUEST['username']) ) {
		//Recherche de l'utilisateur dans le fichier de conf
		$xpath = "//users/user[label='".$_REQUEST['username']."']" ;
		$ListUser = $GLOBALS["config"]->xpath($xpath);
		$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
		$result['time recherche'] = $time;

		// Si  l'utilisateur a été trouvé
		if ( count($ListUser) == 1 ) {
			foreach($ListUser as $user) {
				if ( !isset($_REQUEST['crypt_pass']) )
					$_REQUEST['crypt_pass'] = md5($_REQUEST['password']);
				else
					$_REQUEST['crypt_pass'] = strtolower($_REQUEST['crypt_pass']);
				
				//Si le mot de passe correspond
				if ( $user->pass == $_REQUEST['crypt_pass'] ) {
					// Si le compte n'est pas actif
					if ( $user->actif != "on" ) {
						fn_Trace($_REQUEST["username"].' compte non actif', "acces");
						$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
						$result['time inactif'] = $time;
						$result['login'] = false;
						$result['msg'] = fn_GetTranslation('compte_non_actif');
					}
					else {
						// Si le compte est actif
						/*
						if (isset($GLOBALS["config"]->general->namesession))
							session_name((string)$GLOBALS["config"]->general->namesession.'_'.(string)$_REQUEST['username']);
						else
							session_name("installation_of_ipx800multicard_".(string)$_REQUEST['username']);
						session_start();
						*/
						//$login->set_session(); // Création de la session
						$_SESSION["SuccessLogin"] = true;
						$_SESSION["Privilege"] = (int)$user->privilege;
						$_SESSION["LoginConn"] = (string)$_REQUEST['username'];
						$_SESSION["AuthId"] = (string)$user->attributes()->numero;
						$_SESSION["Timezone"] = (string)$user->timezone;
						$_SESSION["ClientIP"] = getenv("HTTP_X_FORWARDED_FOR") ? getenv("HTTP_X_FORWARDED_FOR") : getenv("REMOTE_ADDR");
						$_SESSION["ApiKey"] = (string)$user->apikey;
						$_SESSION["Debit"] = "1";
						$_SESSION["TypeConnexion"] = 'Compte';
						$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
						$result['time fin session'] = $time;
						$result['login'] = true;
						$result['msg'] = $_SESSION["LoginConn"] . ' ' . fn_GetTranslation('connected');
						
						//'privi'  => $_SESSION["Privilege"],
						date_default_timezone_set($_SESSION["Timezone"]);
						
						//Pushto sur le compte 1
						/*
						if ( $user->pushto != "" and $user->notifier=='on') {
							fn_PushTo(fn_GetTranslation('push_good_connexion', $_SESSION["LoginConn"], $_SESSION["ClientIP"]), $user->pushto);
						}
						*/
						$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
						$result['time fin pushto'] = $time;

					}
				}
				else { //Si le mot de passe ne correspond pas
					$result['login'] = false;
					$result['msg'] = fn_GetTranslation('error_connexion');
					fn_Trace($_REQUEST["username"].' mot de passe incorrect', "acces");
				}
			}
		}
		else { //L'utilisateur n'a pas été trouvé
			$result['login'] = false;
			$result['msg'] = fn_GetTranslation('error_connexion');
			if (isset($_REQUEST["username"]))
				fn_Trace($_REQUEST["username"].' mot de passe incorrect', "acces");
		}
	}
	
	
	else { //Le login n'est pas transmis
		$result['login'] = false;
		$result['msg'] = fn_GetTranslation('error_connexion');
	}
	
	$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
	$result['time fin'] = $time;

	echo json_encode($result);
?>