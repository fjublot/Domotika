<?php 
	function fn_SessionInfo() { 
		$current_session_id = session_id();
		$current_session_name = session_name();
		$html  = '<div id="sessioninfo" style="display:none">';	
		$html .= '	<div id="title">Session name : '.$current_session_name.'</div>';
		$html .= '	<div id="corps">';
		$html .= '  	<div class="form-horizontal form-label-left">';
		$html .= '			<div class="form-group" style="margin-bottom:0;">';
		$html .= ' 				<label class="col-md-4 col-sm-4 col-xs-12">Session id</label>';
		$html .= '				<span  class="col-md-8 col-sm-8 col-xs-12">'.$current_session_id.'</span>';
		$html .= '			</div>';
		foreach($_SESSION as $k => $v) {
			$html .= '		<div class="form-group" style="margin-bottom:0;">';
			$html .= ' 			<label class="col-md-4 col-sm-4 col-xs-12">'.$k.'</label>';
			$html .= '			<span  class="col-md-8 col-sm-8 col-xs-12">'.$v.'</span>';
			$html .= '		</div>';
		}
		$html .= '	</div>';		
		$html .= '</div>';		
		$html .= '</div>';
		return $html;
	}

?>