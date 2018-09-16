<?php 

	function fn_HtmlBinaryAuthField($class, $numero, $value, $field_label, $field_help, $classlabel="col-lg-10 col-md-10 col-sm-10 col-xs-10", $classinput="col-lg-1 col-md-1 col-sm-1 col-xs-1") {
		$checked='';
		if ($value=='1')
			$checked='checked';
		$return  = '<div class="form-group">';
		$return .= ' 	<input type="checkbox" data-toggle="toggle" data-classname="'.$class.'" data-numero="'.$numero.'" class="binaryauth form-control ' . $classinput . '"  id="'.$class.'-'.$numero.'" name="'.$class.'-'.$numero.'" '.$checked.' >';
		$return .= ' 	<label for="'.$class.'-'.$numero.'" class="labelselectauth ' .$classlabel. '">'.$field_label.'</label>';
		//$return .= fn_Help($field_help);
		$return .= '</div>';
		return $return;
	}
?>