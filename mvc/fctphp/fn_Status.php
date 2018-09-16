<?php 
	function fn_Status() { 
		$start_time = microtime(true);
		$Status='';
		$Status.= 'Domicile';
		$Status.=PHP_EOL;
		$Status.=date("d/m/Y").' - '.date("G:i:s");
		$Status.=PHP_EOL;
		$Status.= '_______________________';
		$Status.=PHP_EOL;
		if ( isset($GLOBALS["config"]) ) {
			foreach($GLOBALS["config"]->{"cartes"}->{"carte"} as $carte) {
				$start_time_carte = microtime(true);
				$model = fn_GetModel("carte", $carte->attributes()->numero);
				$current_carte = new $model($carte->attributes()->numero);
				$Status .= PHP_EOL . strtoupper($current_carte->label)."(" . $current_carte->model . ")".PHP_EOL;
				if ( $current_carte->active == "on" ) {
					$current_carte->get_status();
					if ( $current_carte->status !== false) {
						foreach($current_carte->subclass as $class) {
							if (isset($GLOBALS["config"]->{$class."s"}->$class)) {
								foreach($GLOBALS["config"]->{$class."s"}->$class as $info) {
									if ( fn_GetAuth($_SESSION["AuthId"], $class, $info->attributes()->numero) )	{
										if ((string)$info->carteid == (string)$carte->attributes()->numero) {
											$current = new $class((string)$info->attributes()->numero, $info);
											$current->getxmlvalue($current_carte->status);
											if ($class=="relai" || $class=="btn" || $class=="an" || $class=="cnt")
											$Status .= $current->asstring();
										}
									}
								}
							}
						}

					}
					else {
						$Status .= fn_GetTranslation('error_communication', $current_carte->label).PHP_EOL;
					}
				}
				
				else
					$Status .= fn_GetTranslation('desactivation_carte', $current_carte->label).PHP_EOL;
			}
		}
		$Status .= ceil((microtime(true) - $start_time)*1000)." ms";
		return $Status;
	}
?>
