<?php 
	function fn_TraceInstall()
	{
		$url = "http://multicardipx800.sourceforge.net/admin/fn_TraceInstall.php?";
		$url .= "version=". (int)$GLOBALS["config"]->majeur_version .".". (int)$GLOBALS["config"]->mineur_version;
		$url .= "&email=".$GLOBALS["config"]->general->mailadmin;
		if ( $GLOBALS["config"]->general->informmail == "on" )
			$url .= "&informmail=".$GLOBALS["config"]->general->informmail;
		else
			$url .= "&informmail=0=";
		$url .= "&id=".md5($GLOBALS["config"]->general->mailadmin);
		$url .= "&php_version=".urlencode(phpversion());
		foreach (array("carte", "an", "btn", "camera", "cnt", "relai", "variable", "vartxt", "pushto", "scenario", "user") as $class )
		{
			if ( isset($GLOBALS["config"]->{$class."s"}) )
				$url .= "&".$class."=".count($GLOBALS["config"]->{$class."s"}->{$class});
		}
		@wget($url);
	}
?>