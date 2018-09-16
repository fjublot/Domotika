<?php
	include($GLOBALS["mvc_path"].'loadfunctions.php');
	if ( get_magic_quotes_gpc() ) {
		array_walk_recursive($_GET, 'fn_StripSlashesGpc');
		array_walk_recursive($_POST, 'fn_StripSlashesGpc');
		array_walk_recursive($_COOKIE, 'fn_StripSlashesGpc');
		array_walk_recursive($_REQUEST, 'fn_StripSlashesGpc');
	}
	// Le fichier de conf exite déjà et n'a pas encore été chargé
	if ( file_exists($GLOBALS["config_file"]) && ! isset($GLOBALS["config"]) ) {
		libxml_use_internal_errors(true);
		$GLOBALS["config"] = simplexml_load_file($GLOBALS["config_file"]);
		if ( ! $GLOBALS["config"] )	{
			$xml = file($GLOBALS["config_file"]);
			$errors = libxml_get_errors();
			echo "Erreur XML<br>".PHP_EOL;
			foreach ($errors as $error) {
				echo "Erreur config : ".fn_DisplayXmlError($error, $xml)."<br>".PHP_EOL;
			}
			die("Impossible de continuer");
			libxml_clear_errors();
		}
		
		if ( ( isset($GLOBALS["config"]->general->debug) && $GLOBALS["config"]->general->debug == 1 ) or ( isset($_REQUEST["debug"]) and $_REQUEST["debug"] == 1 ) ) {
			error_reporting(E_ALL);
			ini_set("display_errors", "stdout");
		}
		if ( isset($GLOBALS["config"]->general->timezone) && $GLOBALS["config"]->general->timezone != "" )
			date_default_timezone_set($GLOBALS["config"]->general->timezone);
	}
	elseif ( ! isset($GLOBALS["config"]) ) {
		if ( ( isset($_REQUEST["debug"]) and $_REQUEST["debug"] == 1 ) ) {
			error_reporting(E_ALL);
			ini_set("display_errors", "stdout");
		}
		$GLOBALS["config"] = new SimpleXMLElement('<?xml version="1.0"?><config></config>');
	}
	
	$default_config_file = $GLOBALS["root_path"].'default.conf';
	if ( file_exists($default_config_file) ) {
		$default_config = simplexml_load_file($default_config_file);
		fn_MergeXmlChild($GLOBALS["config"], $default_config);
	}

	if ( ! isset($_SESSION['debit']) )
		$_SESSION['debit'] = 1;

	if ( ! isset($GLOBALS["config"]->general->lang) )
		$GLOBALS["config"]->general->lang = "fr";

	if ( file_exists("lib") ) {
		if ($handle = opendir('lib')) {
			while (false !== ($entry = readdir($handle))) {
				if ( preg_match("/\.php$/", $entry) )
					require_once($GLOBALS["root_path"].'lib/'.$entry);
			}
			closedir($handle);
		}
	}
	if ( !isset($_SERVER['SERVER_NAME']) )
		$_SERVER['SERVER_NAME'] = (string)$GLOBALS["config"]->general->namesession;
?>