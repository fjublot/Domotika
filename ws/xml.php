<?php 
	$start_time = microtime(true);
	header('Content-Type: text/xml; charset: UTF-8');
	header("Cache-Control: no-cache");
	echo '<?xml version="1.0" encoding="UTF-8" ?>';
	echo '<root>';
	echo "<starttime>".ceil((microtime(true) - $start_time)*1000)." ms</starttime>";
	echo "<authid>".$_SESSION["AuthId"]."</authid>";


	if ( isset($_REQUEST['detail']) ) {
		$_REQUEST['detail'] = true;
	}
	else {
		$_REQUEST['detail'] = false;
	}
	echo "<inittime>".ceil((microtime(true) - $start_time)*1000)." ms</inittime>";
	echo "<debug>".$GLOBALS["config"]->general->debug."</debug>";


	if ( isset($GLOBALS["config"]) ) {
		echo "<cartes>";
		if (isset($GLOBALS["config"]->{"cartes"})) {
		foreach($GLOBALS["config"]->{"cartes"}->{"carte"} as $carte) {
			$start_time_carte = microtime(true);
			$model = fn_GetModel("carte", $carte->attributes()->numero);
			$current_carte = new $model($carte->attributes()->numero);
			echo '<carte numero="'.$carte->attributes()->numero.'">';
			echo "<model>".$current_carte->model."</model>";
			echo "<label>".$current_carte->label."</label>";
			echo "<active>".$current_carte->active."</active>";
			if ( $current_carte->active == "on" ) {
				$current_carte->get_status();
				//echo $current_carte->status;
				if ( $current_carte->status !== false) {
					if ( $GLOBALS["config"]->general->debug == "on" ) {
						if (property_exists($model, 'portm2m')) {
							if ( $current_carte->portm2m != "" )
								echo "<mode>M2M</mode>";
							else {
								echo "<mode>http</mode>";
								echo "<url>".$current_carte->geturl()."status.xml</url>";
							}
						}
						else {
							echo "<mode>http</mode>";
							echo "<url>".$current_carte->geturl()."ZAutomation/api/v1/devices</url>";
						}
					}
					foreach($current_carte->subclass as $class) {
						$start_time_class = microtime(true);	
						
						if (isset($GLOBALS["config"]->{$class."s"}->$class)) {
							foreach($GLOBALS["config"]->{$class."s"}->$class as $info) {
								if ( fn_GetAuth($_SESSION["AuthId"], $class, $info->attributes()->numero) )	{
									if ((string)$info->carteid == (string)$carte->attributes()->numero) {
										$current = new $class((string)$info->attributes()->numero, $info);
										$current->getxmlvalue($current_carte->status);
										echo $current->asxml($_REQUEST['detail']);
										if ($GLOBALS["config"]->general->mysql_xml == "on" ) {
											$current->update_time = $carte[$current->carteid]->update_time;
											$current->mysql_save();
										}
									}
								}
							}
						}
						echo "<looptime".$class.">".ceil((microtime(true) - $start_time_class)*1000)." ms</looptime".$class.">";

					}
				}
				else {
					echo "<erreur>".htmlspecialchars(ucfirst(fn_GetTranslation('error_communication', $current_carte->label))).".</erreur>";
				}
				
			}
			
			else
				echo "<warning>".htmlspecialchars(fn_GetTranslation('desactivation_carte', $current_carte->label)).".</warning>";
			echo "<looptimecarte>".ceil((microtime(true) - $start_time_carte)*1000)." ms</looptimecarte>";
			echo "</carte>";
		
		}
		}
		echo "</cartes>";
		echo "<espdevices>";
		if (isset($GLOBALS["config"]->{"espdevices"}->{"espdevice"})) {
			foreach($GLOBALS["config"]->{"espdevices"}->{"espdevice"} as $espdevice) {
				$start_time_espdevices = microtime(true);
				$current = new espdevice($espdevice->attributes()->numero);
				echo $current->asxml($_REQUEST['detail']);
			}
		}
		echo "</espdevices>";
		echo "<bdd>";
			if (isset($GLOBALS["config"]->{"variables"})) {
				foreach($GLOBALS["config"]->{"variables"}->{"variable"} as $variable) {
					$start_time_carte = microtime(true);
					$current_variable = new variable($variable->attributes()->numero);
					echo '<variable numero="'.$variable->attributes()->numero.'">';
					echo $current_variable->asxml($_REQUEST['detail']);
					
					echo "</variable>";
				}
			}
		echo "</bdd>";

		echo '<jour>'.date("d/m/Y").'</jour>';   
		echo '<time>'.date("G:i:s").'</time>';   
		echo "<debugtime>".ceil((microtime(true) - $start_time)*1000)." ms</debugtime>";
	 
	}
	echo "</root>";
?>
