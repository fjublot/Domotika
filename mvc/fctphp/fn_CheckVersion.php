<?php 
	function fn_CheckVersion($forpushto = false) {
		$retour = false;
		$post_url = "";
		$pre_version = "";
		if ( isset($GLOBALS["config"]->general->scan_version_dev) && $GLOBALS["config"]->general->scan_version_dev == 'on') {
			$post_url = "_dev";
			$pre_version = "Beta ";
		}
		$url = "http://download.sourceforge.net/project/multicardipx800/version".$post_url.".xml";
		try {
			$sourceforge = @fn_WgetXml($url);
		}
		catch (Exception $e) {
			$sourceforge = false;
		}
		if ( $sourceforge !== false ) {
			fn_Trace($pre_version.$sourceforge->majeur_version.".".$sourceforge->mineur_version, 'version');
		}
		else {
			fn_Trace($pre_version."Version introuvable", 'version');
			//$sourceforge->majeur_version = 1;
			//$sourceforge->mineur_version = 0;
		}
		if ($sourceforge==false)
			$retour .= "<div class='alert'>".htmlspecialchars(fn_GetTranslation('sourceforge_version_error')).".</div>";
		else {
			if ( $GLOBALS["config"]->syno == "true" ) {
				$retour .= htmlspecialchars(fn_GetTranslation('maj_syno'))."<br>";
			}
			elseif ( (int)$GLOBALS["config"]->majeur_version < (int)$sourceforge->majeur_version || (int)$GLOBALS["config"]->mineur_version < (int)$sourceforge->mineur_version) {
				$retour = fn_GetTranslation('new_version', (string)$sourceforge->majeur_version, (string)$sourceforge->mineur_version)."<br>";
				if ( is_file($GLOBALS["root_path_inc"]."install_multisiteipx800.php") && is_writeable($GLOBALS["root_path"]) ) {
					$retour .= "<form method='post' action='install_multisiteipx800.php'>";
					$retour .= "";
					if ( isset($GLOBALS["config"]->general->scan_version_dev) && $GLOBALS["config"]->general->scan_version_dev == "on")
						$retour .= "<input type='hidden' name='type' value='Developpement'/>";
					else
						$retour .= "<input type='radio' name='type' value='Production'/>";
					$retour .= "<input type='hidden' name='ou' value='multicardipx800'/>";
					$retour .= "<div id='composebuttons' class='formbuttons'>";
					$retour .= "<input type='submit' class='button alert' name='install' value='".fn_GetTranslation('do_auto_update',(string)$sourceforge->majeur_version, (string)$sourceforge->mineur_version)."'/> </form></div>";
				}
				else {
					$retour .= "<div class='alert'><a href='http://downloads.sourceforge.net/project/multicardipx800/multicardipx800_V".(string)$sourceforge->majeur_version.".".(string)$sourceforge->mineur_version.".zip'>";
					$retour .= htmlspecialchars(fn_GetTranslation('can_download_here'))."</a></div>";
				}
			}
		}
	  if ($forpushto !== false)
			$retour = fn_GetTranslation('new_version', (string)$sourceforge->majeur_version, (string)$sourceforge->mineur_version);
		return $retour;
	}
?>