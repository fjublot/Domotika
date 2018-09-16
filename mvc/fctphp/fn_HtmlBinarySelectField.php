<?php 
	function fn_HtmlBinarySelectField($field, $value, $field_label, $field_help, $disable=false, $classlabel="control-label col-md-3 col-sm-3 col-xs-12", $classinput="col-lg-1 col-md-6 col-sm-6 col-xs-12") {
		$checked='';
		if ($value=='on')
			$checked='checked';
		$return  = '<div class="form-group">'.PHP_EOL;
		$return .= ' 	<label for="'.$field.'" class="' .$classlabel. '">'.fn_HtmlSpanLabel($field_label).'</label>'.PHP_EOL;
		$return .= '    '.fn_Help($field_help);
		$return .= '	<div class="col-md-6 col-sm-6 col-xs-12">'.PHP_EOL;
		$return .= ' 		<input type="checkbox" data-toggle="toggle" class="form-control ' . $classinput . '"  id="'.$field.'" name="'.$field.'" '.$checked.' >'.PHP_EOL;
		$return	.= '	</div>'.PHP_EOL;
		$return .= '</div>'.PHP_EOL;
		return $return;
	}
?>
