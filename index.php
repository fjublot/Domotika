<?php
	date_default_timezone_set('Europe/Paris');
	$GLOBALS["root_path"]          = __DIR__ . DIRECTORY_SEPARATOR;
	$GLOBALS["page_path"]          = $GLOBALS["root_path"].'pages' . DIRECTORY_SEPARATOR;
	$GLOBALS["page_inc_path"]      = $GLOBALS["page_path"].'inc' . DIRECTORY_SEPARATOR;
	$GLOBALS["page_xml_path"]      = $GLOBALS["page_path"].'xml' . DIRECTORY_SEPARATOR;
	$GLOBALS["page_cams_path"]     = $GLOBALS["page_path"].'cams' . DIRECTORY_SEPARATOR;
	$GLOBALS["class_path"]         = $GLOBALS["root_path"].'class' . DIRECTORY_SEPARATOR;
	$GLOBALS["classexternal_path"] = $GLOBALS["class_path"].'external' . DIRECTORY_SEPARATOR;
	$GLOBALS["model_path"]         = $GLOBALS["root_path"].'model' . DIRECTORY_SEPARATOR;
	$GLOBALS["mvc_path"]           = $GLOBALS["root_path"].'mvc' . DIRECTORY_SEPARATOR;
	$GLOBALS["lib_path"]           = $GLOBALS["root_path"].'lib' . DIRECTORY_SEPARATOR;
	$GLOBALS["config_file"]        = $GLOBALS["root_path"].'config/config.conf';

	include($GLOBALS["mvc_path"].'loadconfig.php');
	include($GLOBALS["mvc_path"].'autoload.php');
	include($GLOBALS["mvc_path"].'model.php');
	include($GLOBALS["mvc_path"].'top.php');

	$phpfile = fn_FindExec("php");
	if ( $phpfile == false )
			$phpfile = '{path}' . DIRECTORY_SEPARATOR . 'php';
	$GLOBALS["php_path"]                = $phpfile;

	
	$appMain = array('MN','MAIN');
	$appWs   = array('WS', 'WEBSERVICE', 'WEBSERVICES');
	$appSms  = array('SMS', 'SMALLMESSAGESERVICE');
	$appApi  = array('API');	
	$GLOBALS["classDispList"] = "col-lg-3 col-md-3 col-sm-4 col-xs-12 thumb animated flipInY";


	$keys = array('app', 		 // MN, WS, SMS 
				  'page', 		 // nom de la page en include
				  'class',		 // nom de la class
				  'model',       // modèle de la classe (sous classe pour les cartes)
				  'addnew',  	 // Mode creation autorisé (true/false)
				  'numero',      // Numero de l'item 
				  'action',      // action pour une forme BT_Envoyer, BT_Annuler, BT_Supprimer, BT_SetCnt 
				  'valsetcnt',    // valeur du compteur à forcer
				  'filtre_carteid', //Filtre carte sur List.php
				  'vide',
				  'carteid',
				  'userid',
				  'filtre_numero',
				  'filtre_type',
				  'type',
				  'prefix',
				  'dossier',
				  'nb_data', //JSONP
				  'min', //JSONP
				  'max', //JSONP
				  'as_array', //JSONP
				  'callback', //JSONP
				  'addone2idx',
				  'rasp433',
				  'value',
				  'asxml',
				  'relai',
				  'espdevice',
				  //control.php
				  'control',
				  'directory',
				  'file',
				  'minversion',
				  'extension',
				  'option',
				  'account',
				  'email',
				  'mailadmin',
				  'ip',
				  'prefix',
				  //'value',
				  'account',
				  // Setup
				  'mysqlenabled', // Pour éviter connexion à la base
				  'mysql', 'mysql_db', 'mysql_host', 'mysql_user', 'mysql_password', 'mysql_port',
				  'namesession', 'mysql_xml'/*, 'mailadmin', 'nameadmin'*/, 'informmail',
				  'url', 'debug', 'lang', 'frequence', 'cookie', 'scan_version_dev', 'phppath',
				  'camera', 'message', 'objet', 
				  'nbalerts',
				  'value',
				  'xmlid',
				  'tracetype',
				  'histotype',
				  // Setup User
				  'nameappli',
				  'label',
				  'pass',
				  'mail',
				  'telmobile',
				  'smsenable',
				  'timezone',
				  // xml xmljson
				  'detail',
				  'img',
				  'apikey',
				  'command',
				  //SMS
				  'gsm',
				  'sms',
				  'norefresh',
				  'HTTP_REFERER',
				  'step_serie', // class graphique
				  'type_serie'
				  );
	$query = '';
//	$currenturl='?';
	foreach ( $keys as $key ) {
		if ( isset($_REQUEST[$key]) ) {
			if ($_REQUEST[$key]=="undefined")
				$$key = '';
			else
				if (is_array($_REQUEST[$key]))
					$$key = array_values($_REQUEST[$key]);
				else
					$$key = (string)$_REQUEST[$key];
		}
		else {
			$$key = ''; // default value
		}
	}
	if ($app=='') {
		$app='MN';
	}
	
	if ($page=='' && (in_array(strtoupper ($app), $appWs)))
		$page='push.JSON';

	if ($page=='' && (in_array(strtoupper ($app), $appMain)))
		$page = 'Main';
	
	if ($page=='' && (in_array(strtoupper ($app), $appSms)))
		$page = 'sms';
	
	if ($page=='' && (in_array(strtoupper ($app), $appApi)))
		$page = 'sms';


/*
	foreach ( $keys as $key ) {
		if ($$key != "")
			$currenturl .= '&'.$key.'='.$$key;
	}
*/
	

	
	$login = new login;
	$login->set_session(); // Création de la session même si non loggué
	
	$notlogged = array(	"Login",
						"FirstUse",
						"installdatabase.JSON", 
						"createconfig.JSON",
						"Setup", 
						"logincheck.JSON", 
						"control.JSON", 
						"push.JSON", 
						"list.JSON", 
						"cron", // Lancé par contab
						"sms", // Sur réception de sms
						"api");
	
	$nodb = array(	"Login",
					"FirstUse",
					"installdatabase.JSON", 
					"createconfig.JSON",
					"Setup", 
					"logincheck.JSON",
					"control.JSON",
					"list.JSON");
	
	fn_LoadTraduction();

	if ((file_exists($GLOBALS["config_file"]) && isset($GLOBALS["config"]->general->mysql) && $GLOBALS["config"]->general->mysql=="on")  && $mysqlenabled!="off") {
			include($GLOBALS["mvc_path"].'connbdd.php');
	}
	
	if (in_array($page, $notlogged) 
		|| ((! isset($GLOBALS["config"]->users)) && $page=='Add' && $class=='user')
		|| ((! isset($GLOBALS["config"]->users)) && $page=='submit.JSON')
		) {
		$notlogged = true;
	}
	else {
			include($GLOBALS["page_inc_path"] . 'loginverif.php');
		
	}

	
	switch (strtoupper ($app)) {
			case (in_array(strtoupper ($app), $appMain) ? strtoupper ($app) : ''): //MN
				// $app=menu to access menus, replacement of direct access to menu.php
				if ( ! isset($_SERVER["HTTP_REFERER"]) )
					$_SERVER["HTTP_REFERER"] = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['REQUEST_URI']);
				$fn = $GLOBALS["page_path"].$page.'.php';
				if ($page!="Login") {
					include($GLOBALS["page_inc_path"] . 'head.php');
					echo PHP_EOL . '<!-- ' . $fn . '-->' . PHP_EOL ;
					if (isset($GLOBALS["config"]->users))
						include($GLOBALS["page_inc_path"] . 'topbar.php');
				}
				if (file_exists($fn))
					require $fn;
				else 
					include($GLOBALS["page_inc_path"].'404.php');
						
				if ($page!="Login") {
					include($GLOBALS["page_inc_path"] . 'foot.php');
				}
				break;

			case (in_array(strtoupper ($app), $appWs) ? strtoupper ($app) : ''): //WS
				// $app=webservices to access webservices, replacement of input.php and output.php
				if ($page=='')
					$page='push.JSON';
				
				$fn = $GLOBALS["root_path"].'ws/'.$page.'.php';
				if (file_exists($fn)) {
					include $fn;
				}
				break;

			case (in_array(strtoupper ($app), $appSms) ? strtoupper ($app) : ''): //SMS
				// $app=sms to access sms, replacement of sms.php
				$fn = $GLOBALS["root_path"].'ws/api.JSON.php';
				if (file_exists($fn)) {
					require $fn;
				}
				break;

			case (in_array(strtoupper ($app), $appApi) ? strtoupper ($app) : ''): //API
				// $app=sms to access sms, replacement of sms.php
				$fn = $GLOBALS["root_path"].'ws/api.JSON.php';
				if (file_exists($fn)) {
					require $fn;
				}
				break;
				
			default:
				// error messages
				$error_content = '';
				if (!isset($_SESSION['error_string']))
					$_SESSION['error_string']="";
				if (!isset($err))
					$err="";
					
				if ($err == $_SESSION['error_string']) {
					$error_content = "<div class=error_string>".$err."</div>";
				}
	}

	unset($_SESSION['error_string']);
	exit();


	// error messages
	$error_content = '';
	if ($err = $_SESSION['error_string']) {
		$error_content = "<div class=error_string>$err</div>";
	}
	unset($_SESSION['error_string']);

?>