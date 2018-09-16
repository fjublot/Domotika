<?php 
	function fn_IsVersionInstalledGood()
	{
		if ( (int)$GLOBALS["config"]->general->majeur_version < (int)$GLOBALS["config"]->majeur_version || (int)$GLOBALS["config"]->general->mineur_version < (int)$GLOBALS["config"]->mineur_version )
			return false;
		else
			return true;
	}
?>