<?php 
	function fn_GetVersion()
	{
		$retour = false;
		if ( isset($GLOBALS["config"]->general->scan_version_dev) && $GLOBALS["config"]->general->scan_version_dev == 1)
			$sourceforge = fn_WgetXml("http://download.sourceforge.net/project/multicardipx800/version_dev.xml");
		else
			$sourceforge = fn_WgetXml("http://download.sourceforge.net/project/multicardipx800/version.xml");
		if ( $sourceforge !== false )
			return $sourceforge->majeur_version.".".$sourceforge->mineur_version;
		else
			return "1.0";
	}
?>