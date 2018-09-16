<?php
	if ($numero=="") {
		$return = fn_GetTranslation('pb_request_var', "numero");
	}
	else {
		if ( ! isset($GLOBALS["include_only"]) )
			$droit = fn_GetAuth($_SESSION["AuthId"], 'graphique', $numero);
		else
			$droit = true;
			
		if ( $droit ) { 
			$current = new graphique($numero);
			$return  =  fn_HtmlStartPanel($current->label, ucfirst(fn_GetTranslation('graphique')), 'graphique', $current->numero);
			$return .=  '<div class="graphique" id="container_' . $numero.'"></div>';
			$return .= $current->dispgraph();
		}
		else {
			$return .='<script type="text/javascript">';
			$return .='	new PNotify({';
			$return .='		title: '.fn_GetTranslation("no_right").'\', ';
			$return .='		text: item.message,';
			$return .='		type: \'error\',';
			$return .='		nonblock: false	';	
			$return .='	});';
			$return .='</script>';		
		}
	}

	if ( ! isset($GLOBALS["include_only"]) ) {
		$return .= '</div> <!-- /graphique container -->';
		$return .= '</div> <!-- /panel -->';
	}
	echo $return;
	include($GLOBALS["page_inc_path"] . 'headloadjs.php');	
	
?>                                     