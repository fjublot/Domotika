<?php 
	$starttime = microtime(true);
	header('Content-Type: application/json; charset=utf-8');
	header("Cache-Control: no-cache");
	$comps = explode('.', $starttime);
	$init['starttime'] = date("d/m/Y G:i:s",$starttime).','.$comps[1];
	$cartes="";
	if ( $detail=="")
		$detail = true;
	else 
		$detail = false;
	
	$init['initduration'] = ceil((microtime(true) - $starttime)*1000)." ms";
	$init['debug'] = (string)$GLOBALS["config"]->general->debug;
	
	if ( isset($GLOBALS["config"]) ) {
		if (isset($GLOBALS["config"]->{"cartes"}->{"carte"})) {
			foreach($GLOBALS["config"]->{"cartes"}->{"carte"} as $carte) {
				$start_time_carte = microtime(true);
				$model = fn_GetModel("carte", $carte->attributes()->numero);
				$current_carte = new $model($carte->attributes()->numero);
				$cartes[(string)$carte['numero']] = array(
					'model' => $current_carte->model,
					'label' => $current_carte->label,
					'active' => $current_carte->active
				);
				if ( $current_carte->active == "on" ) {
					$statusxml=$current_carte->get_status();
					if ((string)$GLOBALS["config"]->general->debug == "on")
						$cartes[(string)$carte['numero']]['statusxml'] = $statusxml;
					if ( $current_carte->status != False) {
						if ( $GLOBALS["config"]->general->debug == "on" ) {
						if ( isset($current_carte->portm2m )) {
						
							if ( $current_carte->portm2m != "" )
									$cartes[(string)$carte['numero']]['mode'] = "M2M";
								else {
									$cartes[(string)$carte['numero']]['mode'] = "HTTP";
									$cartes[(string)$carte['numero']]['url'] = $current_carte->geturl()."status.xml";
								}
							}
						}
						foreach($current_carte->subclass as $subclass) {
							$start_time_class = microtime(true);	
							
							if (isset($GLOBALS["config"]->{$subclass."s"}->$subclass)) {
								foreach($GLOBALS["config"]->{$subclass."s"}->$subclass as $info) {
									if ( fn_GetAuth($_SESSION["AuthId"], $subclass, $info->attributes()->numero) )	{
										if ((string)$info->carteid == (string)$carte->attributes()->numero) {
											$current = new $subclass((string)$info->attributes()->numero, $info);
											$current->getxmlvalue($current_carte->status);
											$subclassjson = $current->asjson($detail);
											if ($GLOBALS["config"]->general->mysql_xml == "on" ) {
												$current->update_time = $carte[$current->carteid]->update_time;
												$current->mysql_save();
											}
											$subclasses[] = array($subclass => $subclassjson);	
										}
									}
									
								}
							}
							$subclasses['looptime'.$subclass] = ceil((microtime(true) - $start_time_class)*1000)." ms";
							$cartes[(string)$carte['numero']][$subclass.'s'] = $subclasses;
							unset($subclasses);

						}
				
					}
					else {
						$cartes[(string)$carte['numero']]['erreur'] = htmlspecialchars(ucfirst(fn_GetTranslation('error_communication', $current_carte->label)));
					}
					
				}
				
				else
					$cartes[(string)$carte['numero']]['warning'] = htmlspecialchars(ucfirst(fn_GetTranslation('desactivation_carte', $current_carte->label)));
			$cartes[(string)$carte['numero']]['looptimecarte'] = ceil((microtime(true) - $start_time_carte)*1000)." ms";
			}
		}
		if (isset($GLOBALS["config"]->{"variables"})) {
			foreach($GLOBALS["config"]->{"variables"}->{"variable"} as $variable) {
				$current_variable = new variable($variable->attributes()->numero);
				//$variablejson = $current_variable->asjson($detail);
			}
		}

		if (isset($GLOBALS["config"]->{"espdevices"})) {
			foreach($GLOBALS["config"]->{"espdevices"}->{"espdevice"} as $espdevice) {
				$current_espdevice = new espdevice($espdevice->attributes()->numero);
				$espdevices[] = $current_espdevice->asjson($detail);
			}
		}
	

		
	$endtime = microtime(true);
	$comps = explode('.', $endtime);

	$end['endtime'] = date("d/m/Y G:i:s").','.$comps[1];
	$end['debugtime'] = (ceil($endtime*1000 - $starttime*1000))." ms";
	 
	}
	
	$results = array(
		"init" => $init,
		"cartes" => $cartes,
		"bdd" => $bdd,
		"espdevices" => $espdevices,
		"end" => $end
	);

	echo json_encode($results);
?>
