<?php 
	function fn_Help($HelpMsg) { 
	  if (!isset($_SESSION['lang']))
	   $_SESSION['lang']="fr";
		if ( file_exists("help/".$_SESSION['lang']."/".$HelpMsg.".help") ) {
			$xmlhelp = simplexml_load_file("help/".$_SESSION['lang']."/".$HelpMsg.".help");
			$html  = '<div class="hidden-xs info">';
			$html .= '<abbr rel="tooltip" title="<h1>'.$xmlhelp->titre.'</h1><p>'.$xmlhelp->message.'</p>" >';
			$html .= '<i class="fa fa-question"></i>';
		$html .= '</abbr>';
		$html .= '</div>';
	  }
	  else {
			$html  = '<div class="info" style="visibility:hidden;">';
		$html .= '</div>';
	  }  
	  return $html;
	}
?>