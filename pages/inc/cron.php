<?php
/*
require('LoadConfig.php');
session_name((string)$GLOBALS["config"]->general->namesession);
session_start();
fn_LoadTraduction();
require_once('class/cron.php');
require_once('class/scenario.php');
require_once('fctphp/function.php');
require_once("connect_mysql.php");
*/
if ( isset($_SERVER['HTTP_HOST']) )
	fn_Trace("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],"cron");
else
	fn_Trace("php cron.php","cron");
$current_time = time();
if ( isset($GLOBALS["config"]->crons) )
{
	foreach($GLOBALS["config"]->crons->cron as $info)
	{
		$cron = new cron($info->attributes()->numero, $info);
		if ( $cron->time_to_run($current_time) && $cron->actif == 1 )
		{
			$scenario = new scenario($cron->scenario);
			fn_Trace("Exec scenario ".$cron->scenario, "cron");
			if ( $scenario->asynchrone == "0" )
			{
				eval($scenario->code);
				fn_Trace("Exec scenario ".$cron->scenario, "scenario");
			}
			else
			{
				$phpfile = $GLOBALS["config"]->general->phppath;
				$txtexec = $phpfile." runscenario.php ".$scenario->numero." >> trace/".$scenario->numero.".log 2>&1 &";
				if ( $GLOBALS["config"]->general->phppath !== false )
				{
					exec($txtexec);
					fn_Trace($txtexec,"xml");
				}
				else
				{
					fn_Trace("Exec scenario ".$cron->scenario, "scenario");
					eval($scenario->code);
				}
			}
		}
	}
}
if ( isset($GLOBALS["config"]->general->mysql) && $GLOBALS["config"]->general->mysql == 'on' )
{
	if ( isset($GLOBALS["config"]->cartes) )
	{
		require_once("class/carte.php");
		foreach($GLOBALS["config"]->cartes->carte as $info)
		{
			$model = fn_GetModel($info->attributes()->numero);        
			$current_carte = new $model($info->attributes()->numero, $info);
			fn_Trace("Lecture carte ".$current_carte->numero, "cron");
            $current_carte->get_status();
			if ( $current_carte->status !== false )
			{
				$xpath = "//*[carteid='".$current_carte->numero."']";
				$nodes = $GLOBALS["config"]->xpath($xpath);
				if ( count($nodes) != 0 )
				{
					foreach ($nodes as $item)
					{
						$class = $item->getName();
						$current = new $class($item->attributes()->numero, $item);
						$current->getxmlvalue($current_carte->status);
						$current->update_time = $current_carte->update_time;
						$current->mysql_save();
					}
				}
			}  
		}
	}
}
?>