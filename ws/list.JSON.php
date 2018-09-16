<?php
	/*----------------------------------------------------------------------*
	* Titre : List.JSON.php                                                  *
	* Programme permettant de lister au format json les objets d'une classe *
	*----------------------------------------------------------------------*
	$_REQUEST("class");
	$_REQUEST("vide");
	$_REQUEST("carteid");
    $_REQUEST("class");
    $_REQUEST("filtre_numero");
    $_REQUEST("filtre_carteid");
	$_REQUEST("filtre_type");
	$_REQUEST("type");
	$_REQUEST("prefix");
	$_REQUEST("dossier");
	$_REQUEST("addone2idx");
	$_REQUEST("tracetype");
	$_REQUEST("histotype");
	
	
	*/
	
	header('Content-Type: application/json; charset=utf-8');
	//fn_Trace("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],"json");
	$result = array();
	//$result[] = array('carteid'=>$carteid);
	//$result[] = array('class'=> $class, 
	//				'carteid' => $carteid);
					
	// ajout d'une valeur vide en cas de présence du paramètre "vide" (true)
	if ( $vide!="" ) {
		$result[] = array(
			'id' => "#N/A",
			'text' => fn_GetTranslation('none')
		);
	}
	
	// rapatrier les images d'un dossier sur présence du paramètre "dossier"
	if ($dossier!="") {
		$list_image = array();
		if ( isset($dossier) && is_dir("images/".$dossier) )
			$dossier = "images/".$dossier;
		else
			$dossier = "images/";
		if ( $dh = opendir($dossier) ) {
			while (($file = readdir($dh)) !== false) {
				if ( is_file($dossier."/".$file) && $file != ".htaccess" && $file != "Thumbs.db" )
					$list_image[$file] = $dossier."/".$file;
			}
			closedir($dh);
		}
		//$GLOBALS["root_path"].
		if ( $dh = opendir("config/images") ) {
			while (($file = readdir($dh)) !== false) {
				if (  is_file("config/images/".$file) && $file != ".htaccess" && $file != "Thumbs.db" )
					$list_image[$file] = "config/images/".$file;
			}
			closedir($dh);
		}
		ksort($list_image);
		foreach($list_image as $name => $file) {
			$result[] = array(
				'id' => $file,
				'text' => $name,
				'image' =>$file
			);
		}
	}
	
	elseif ($class=="graphmode") {
		if ( fn_AccesHighCharts() )
			$result[] = array(
				'id' => "Chart",
				'text' => "Graphique"
			);
		if ( fn_AccesHighCharts() )
			$result[] = array(
				'id' => "StockChart",
				'text' => "Graphique Zoom"
			);
	}
	
	elseif ($class== "timezone" ) {
		$timezone = array();
		foreach(DateTimeZone::listAbbreviations() as $zone)
			foreach($zone as $subzone)
				array_push($timezone, $subzone["timezone_id"]);
		$timezone = array_unique($timezone);
		sort($timezone);
		foreach($timezone as $zone) {
			if ( ! is_null($zone) ) {
				$result[] = array(
					'id' => $zone,
					'text' => $zone
				);
			}
		}
	}
	
	elseif ( $class == "fonction" ) {
		$result = $result + fn_GetListHelpFile("fonction","array");
	}
	
	// Class uniquement sans carteid (liste totale)
	elseif (( $class!="") && ($carteid=="")) {
		$result = fn_GetListItem($class, $filtre_numero, $carteid, $filtre_type, $prefix, "array", $vide); 
	}


	// Liste les relais, btns, ans en creation pour une carte 
	elseif ($carteid!="" && $class!="" && $xmlid=="") {
		$result = fn_GetUnUsedListItem($class, $carteid, "array", false ,$vide, $addone2idx);
	}
	// pour traiter les xmlid
	elseif ($carteid!="" && $class!="" && $xmlid==true) {
		$result = fn_GetListXmlId($carteid, $class);
	}
	
	elseif ($tracetype!="") {
		$tracetypes=$GLOBALS['db']->getTraceTypes();
		foreach($tracetypes as $tracetype) {
			if ( ! is_null($tracetype) ) {
				$result[] = array(
					'id' => $tracetype["type"],
					'text' => $tracetype["type"]
				);
			}
		}
	}
	elseif ($histotype!="") {
		$classlist = array('relai', 'razdevice', 'btn', 'an', 'cnt', 'variable', 'vartxt');
		foreach($classlist as $class) {
			$listitems = fn_GetListItem($class, "", "", "", "");
			foreach($listitems as $listitem) {
				$result[] = array(
					'id' => $class."|".$listitem["id"],
					'text' => "[" . $class . $listitem["id"] . "] - " . $listitem["text"]
				);
			}
		}
	}
	

	echo json_encode($result);
?>