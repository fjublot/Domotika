<?php
	header('Content-Type: application/json; charset=utf-8');
	$doc = new domDocument();
	$doc->preserveWhiteSpace = FALSE;
	$doc->formatOutput = TRUE;
	$doc->loadXml('<?xml version="1.0" encoding="UTF-8"?><config></config>');

	if ( file_exists($GLOBALS["config_file"]) ) {
		for ($count = 9; $count > 0; $count--) {
			if ( file_exists($GLOBALS["root_path"]."config/config.conf~".$count."~") )
				copy($GLOBALS["root_path"]."config/config.conf~".$count."~", $GLOBALS["root_path"]."config/config.conf~".($count+1)."~");
		}
		copy($GLOBALS["config_file"], $GLOBALS["root_path"]."config/config.conf~1~");
			$doc->load($GLOBALS["config_file"]);
	}
	else {
		$doc->loadXml('<?xml version="1.0" encoding="UTF-8"?><config></config>');
	}
	{
		// Création du fichier XML
		$xpathdoc=new DOMXPath($doc);
		
		$List = $xpathdoc->query('//config/general');
		if ( $List->length == 0 ) {
			$List = $xpathdoc->query('//config');
			if ( $List->length == 0 ) {
				$New = $doc->createElement('config');
				$List = $List->appendChild($New);
			}
			else
				$List = $List->item(0);
			$New = $doc->createElement("general");
			$general = $List->appendChild($New);
		}
		else {
			$general = $List->item(0);
		}
		$namesession = fn_RemoveAccents(str_replace(' ','',$nameappli));
		$mineur_version = "100";
		$majeur_version = "4";
		
		$fields = array(
			"mineur_version",
			"majeur_version",
			"nameappli", 
			"frequence",
			"cookie",
			"namesession", 
			"mysql", 
			"mysql_host", 
			"mysql_port", 
			"mysql_user", 
			"mysql_password", 
			"mysql_db", 
			"mysql_xml", 
/*			"mailadmin", 
			"nameadmin", 
*/			"debug", 
			"lang", 
			"scan_version_dev",
			"phppath",
			"url",
			"timezone");
		$boolfields = array(
			"mysql", 
			"mysql_xml", 
			"debug", 
			"scan_version_dev");
			

		foreach($fields as $field) {
			$List = $xpathdoc->query('//config/general/'.$field);
			if ( $List->length <> 0 ) {
				$general->removeChild($List->item(0));
			}
			if (isset($$field) && $$field=="" && in_array($field, $boolfields)) {
				$New = $doc->createElement($field, (string)utf8_encode('off'));
				$current = $general->appendChild($New);
			}
			else {
				if ( isset($$field) ) {
					$New = $doc->createElement($field, (string)utf8_encode($$field));
					$current = $general->appendChild($New);
				}
			}
			
		}
		$result=array();
		if ( ! $doc->save($GLOBALS["config_file"]) ) {
			$message[] = fn_GetTranslation('create_file_impossible', $GLOBALS["config_file"]);
			$valid = false;
		}
		else {
			$message[] = fn_GetTranslation('config_update');
			$valid = true;
		}
	}
	$result['valid'] = $valid;
	$result['message'] = $message;
	$result['phppath'] = $phppath;
	
	// Encodage du tableau en json			
	echo json_encode($result);		
?>