<?php
	$CurrentIP = fn_GetIP();
	$nextpage="";
	$noconfig=false;
	$nouser=false;
	if (!file_exists($GLOBALS["config_file"])) { // le fichier de conf n'existe pas
		$noconfig =  true;
		$nextpage="Setup";
	} 
	elseif  (!isset($GLOBALS["config"]->users)) { //il n'y a pas de user d�fini
		$nouser = true;
		$nextpage = "Add&class=user";
	}
	/*
	echo fn_IsVersionInstalledGood();
	
	if (!fn_IsVersionInstalledGood() && (! isset($_SESSION['Privilege']) || $_SESSION['Privilege'] >= (int)$GLOBALS["config"]->access->param )) {
		// ( la version n'est pas la derni�re et (pas de privil�ge ou privil�ges >= droits Master) (90 par d�faut)
		$msg =  "Mise � jour version";
		$nextpage = "Update";
	}
	*/

	if ( ! isset($_SESSION["LoginConn"])) { //Si le login est inconnu en session 
	//si des connexionsauto sont d�finies
		if ( isset($GLOBALS["config"]->connexionautos) ) {
			foreach($GLOBALS["config"]->connexionautos->connexionauto as $connexionauto) {
				//list($ip, $reseau) = explode("/", $connexionauto->ip);
				$ips = explode("/", $connexionauto->ip);
				$ip = @$ips[0];
				$reseau = @$ips[1];
				
				if ( $reseau=='')  { //pas de masque reseau mais juste une ip
					//$ip = gethostbyname($ip);
					$minip = fn_IpAsLong($ip);
					$maxip = fn_IpAsLong($ip);
				}
				else {
					$minip = fn_IpAsLong($ip);
					$maxip = fn_IpAsLong($ip)+pow(2, (32-$reseau));
				}
				
				if ( $minip <= fn_IpAsLong($CurrentIP) && fn_IpAsLong($CurrentIP) <= $maxip && $connexionauto->actif=="on") {
					$xpath = "//users/user[@numero='".$connexionauto->compte."']";
					$ListUser = $GLOBALS["config"]->xpath($xpath);
				
				if ( count($ListUser) == 1) {
						foreach($ListUser as $user) {
						
							$_SESSION["SuccessLogin"] = true;
							$_SESSION['LoginConn'] = (string)$user->label;
							$_SESSION['Privilege'] = (string)$user->privilege;
							$_SESSION['AuthId']    = (string)$user->attributes()->numero;
							$_SESSION["Timezone"]  = (string)$user->timezone;
							$_SESSION["ApiKey"]    = (string)$user->apikey;
							
							$_SESSION["ClientIP"]  = $CurrentIP;
							$_SESSION["TypeConnexion"] = 'IP';
							
						}
					}
				
				}
				
			}
		}
		
		//si login en cookie et pas premi�re install, connexion auto
		if ( isset($_COOKIE["login"]) && isset($GLOBALS["config"]) ) {
			$xpath = "//users/user[label='".$_COOKIE['login']."']";
			$ListUser = $GLOBALS["config"]->xpath($xpath);
			if ( count($ListUser) == 1 ) {
				foreach($ListUser as $user) {
					$_SESSION["SuccessLogin"] = true;				
					$_SESSION['LoginConn'] = (string)$_COOKIE['login'];
					$_SESSION['Privilege'] = (int)$user->privilege;
					$_SESSION['AuthId']    = (string)$user->attributes()->numero;
					$_SESSION["Timezone"]  = (string)$user->timezone;
					$_SESSION["ApiKey"]    = (string)$user->apikey;
					$_SESSION["ClientIP"]  = $CurrentIP;
					$_SESSION["TypeConnexion"] = 'Cookie';
					setcookie("login",(string)$_COOKIE['login']);					
				}
			}
		}
		elseif ( isset($_REQUEST["OnLine"]) && isset($_REQUEST['login']) && isset($_REQUEST['password']) ) {
			$_POST['username'] = $_REQUEST['username'];
			$_POST['crypt_pass'] = $_REQUEST['password'];
			require_once ($GLOBALS["page_inc_path"] . 'logincheck.php');
		}
	}

	if ($nextpage!="") {
		header ("Refresh: 3;URL=?app=Mn&page=" . $nextpage);
		include($GLOBALS["page_inc_path"] . 'head.php');
		include($GLOBALS["page_inc_path"] . 'headloadjs.php');?>
		<script type="text/javascript">
			new PNotify({
				title: '<?php echo ucfirst(fn_GetTranslation("error")); ?>',
				text: '<?php echo $noconfig?ucfirst(fn_GetTranslation('no_config')):ucfirst(fn_GetTranslation('no_user'));?>',
				type: 'error',
				nonblock: false		
			});
		</script>
		<?php
		die();
	}

	// il faut se logguer
	if  ( ! isset($_SESSION["LoginConn"] ) || empty($_SESSION["LoginConn"] ) ) {
		if (strtoupper ($app)=="MN") {
			header("location:?app=Mn&page=Login");
			die();
		}
		else {
			echo 'erreur habilitation';
			die();
		}
	}

?>