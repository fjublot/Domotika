<?php
	function fn_ScenarioRun($no) {
		$current_time = time();
		$scenario = new scenario($no);
		/*
		if ( $scenario->asynchrone == "off" ) {
			eval($scenario->code);
			trace("Exec scenario ".$scenario->label."(".$no.")", "scenario");
		}
		else {
			$phpfile = $GLOBALS["config"]->general->phppath;   
			$txtexec = $phpfile." runscenario.php ".$scenario->numero." >> trace/".$scenario->numero.".log 2>&1 &";
			if ( $GLOBALS["config"]->general->phppath !== false ) {
				exec($txtexec);
				trace($txtexec,"xml");
			}
			else {
				trace("Exec scenario ".$scenario->label."(".$no.")", "scenario");
				eval($scenario->code);
			}
		}
		*/
		return $scenario->run();
	}
?>