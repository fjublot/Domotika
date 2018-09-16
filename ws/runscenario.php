<?php
function TraiterBuffer($buffer)
{
  fn_Trace($buffer,'asynch');
  return("traité");
}
ob_start("TraiterBuffer");
if ( isset($argv[1]) && file_exists("config/scenarios/".$argv[1].".inc") )
{
	echo PHP_EOL.fn_GetTranslation('background_scenario', $argv[1]);   /*'.date("Y-m-d").' - '.strftime("%H:%M:%S %A",time())*/
	$current = new Scenario($argv[1], null);
	fn_Trace("Run asynchrone scenario ".$current->numero, "scenario");
 	eval($current->code);
}
ob_end_flush();
?>