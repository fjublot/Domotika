<?php
	header('Content-Type: text/xml; charset: UTF-8');
	header("Cache-Control: no-cache");
	$start_time = microtime(true);
	echo '<?xml version="1.0" encoding="UTF-8" ?>';
	echo '<root>';
	echo "<debug>".$GLOBALS["config"]->general->debug."</debug>";
	echo '<jour>'.date("d/m/Y").'</jour>';
	echo '<time>'.date("G:i:s").'</time>';
	if ( isset($GLOBALS["config"]->{"scenarios"}) ) {
		$current = new scenario($numero);
		echo '<scenarioname>'.$current->label.'</scenarioname>';	
		
		if ( isset($_SESSION["AuthId"]) && fn_GetAuth($_SESSION["AuthId"], "scenario", $current->numero) ) {
			echo "<message>";
				$current->run();
			echo "</message>";
		}
		else {
			echo "<error>".fn_GetTranslation('usernorighton', $_SESSION["AuthId"], "scenario", $numero).".</error>";
		}
	}
	else {
		echo "<error>".fn_GetTranslation('canot_find_class', $_REQUEST["class"])."</error>";
	}
	$current_time = microtime(true);
	echo "<debugtime>".ceil(($current_time - $start_time)*1000)." ms</debugtime>";
	echo "</root>";
?>