<?php
	$starttime = microtime(true);
	header('Content-Type: application/json; charset=utf-8');
	header("Cache-Control: no-cache");
	$comps = explode('.', $starttime);
	$init['starttime'] = date("d/m/Y G:i:s",$starttime).','.$comps[1];

	if ( isset($_SERVER['HTTP_HOST']) )
		fn_Trace("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],"cron");
	else
		fn_Trace("php cron.php","cron");
	$scenarios = array();
	$current_time = time();
	if ( isset($GLOBALS["config"]->crons) ) {
		foreach($GLOBALS["config"]->crons->cron as $item) {
			$cron = new cron($item->attributes()->numero);
			$scenario = new scenario($cron->scenario);
			
			if ( $cron->time_to_run($current_time) && $cron->actif == 'on' ) {
				$scenarios[] = array ("scenario" => $scenario->label, "torun" => "true");
				fn_Trace("Exec scenario ".$cron->scenario, "cron");
				if ( $scenario->asynchrone == "off" ) { // Si synchrone
					eval($scenario->code);
				}
				else { // Si asynchrone
					$phpfile = $GLOBALS["config"]->general->phppath;
					$txtexec = $phpfile." runscenario.php ".$scenario->numero." >> trace/".$scenario->numero.".log 2>&1 &";
					if ( $GLOBALS["config"]->general->phppath !== false ) {
						exec($txtexec);
						fn_Trace($txtexec,"xml");
					}
					else {
						eval($scenario->code);
					}
				}
			}
			else {
				$scenarios[] = array ("scenario" => $scenario->label, "torun" => "false");
			}
				
		}
	}
	
	if ( (isset($GLOBALS["config"]->general->mysql)) && ($GLOBALS["config"]->general->mysql == 'on' )) {
		if ( isset($GLOBALS["config"]->cartes) ) {
			foreach($GLOBALS["config"]->cartes->carte as $item) {
				if ($item->active=="on") {
					$model = fn_GetModel("carte", $item->attributes()->numero);
					$current_carte = new $model($item->attributes()->numero);
					list($errorHttp, $responseHttp) = $current_carte->get_status();
					$cartes[(string)$item['numero']] = array(
						'model' => $current_carte->model,
						'label' => $current_carte->label,
						'active' => $current_carte->active,
						'errorhttp' => $errorHttp,
						'responseHttp' => $responseHttp,
						'status' => $current_carte->status
						
						
					);

					//if ( $current_carte->status == false ) {
						$xpath = "//*[carteid='".$current_carte->numero."']";
						$nodes = $GLOBALS["config"]->xpath($xpath);
						if ( count($nodes) != 0 ) {
							$i=0;
							$logitem = array();
							foreach ($nodes as $node) {
								$class = $node->getName();
								$model = fn_GetModel($class, $node->attributes()->numero);
								$logitem['numero'] = (string)$node->attributes()->numero;
								$logitem['label'] = (string)$node->label;
								
								$current = new $model($node->attributes()->numero);
								$valconv = $current->getxmlvalue($current_carte->status);
								$logitem['valeur'] = (string)$current->valeur;
								if ( property_exists($model, 'valconv') )
									$logitem['valconv'] = (string)$current->valconv;
								$current->update_time = $current_carte->update_time;
								$logitem['mysql']['sql'] = $current->mysql_save();
								$cartes[(string)$item['numero']][$model.'s'][] = $logitem;
								$i++;
							}
						}
					//} 
				}
			}
		}
	}
	
	$endtime = microtime(true);
	$comps = explode('.', $endtime);

	$end['endtime'] = date("d/m/Y G:i:s").','.$comps[1];
	$end['debugtime'] = (ceil($endtime*1000 - $starttime*1000))." ms";

	if (!isset($cartes))
		$cartes="";
	//echo json_encode(array_merge($scenarios));
		$results = array(
		"init" => $init,
		"scenarios" => $scenarios,
		"cartes" => $cartes,
		"end" => $end
	);

	echo json_encode($results);
	
?>