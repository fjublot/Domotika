<?php 
	function fn_HtmlLabel($field, $field_label, $field_help) { 
		$classlabel = "control-label"; 
		$return  = '<div class="form-group">'.PHP_EOL;
		$return .= '	<div class="icon-valid" id="Valid_'.$field.'" >';
		$return .= '		<div class="success-mark"><span>✔</span></div>';
		$return .= '		<div class="error-mark"><span>✘</span></div>';
		$return .= '		<div class="running-mark"><i class="fa fa-spin fa-spinner fa-2x"></i></div>';
		$return .= '	</div>';
		$return .= ' 	<label id="'.$field.'" class="' .$classlabel. '">'.fn_HtmlSpanLabel($field_label).'</label>'.PHP_EOL;
		$return .= fn_Help($field_help).PHP_EOL;
		$return .= '</div>';
		return $return;
	}
?>