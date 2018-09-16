<?php 
	function fn_HtmlTextAreaField($field, $value, $type, $field_label, $field_help, $required=false, $placeholder="")
	{
		$classlabel = "control-label col-md-3 col-sm-3 col-xs-12"; 
		$classtetarea = "col-md-6 col-sm-6 col-xs-12";
		$return  = '<div class="form-group">';
		$return .= ' 	<label for="'.$field.'" class="' .$classlabel. '">'.fn_HtmlSpanLabel($field_label).'</label>';
		$return .= fn_Help($field_help);
		$return .= '	<div class="col-md-6 col-sm-6 col-xs-12">';
		$return .= '	 <textarea class="100" name="'.$field.'" id="'.$field.'" rows="15" wrap="hard"';
		if ($required==true)
			$return .= ' required ';
		if ($placeholder != "")
			$return.=' placeholder="' . $placeholder . '" ';
		$return .= '>'.$value.'</textarea></div>';
		$return .= '</div>';
		return $return;
	}
?>