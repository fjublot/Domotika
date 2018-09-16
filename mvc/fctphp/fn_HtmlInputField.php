<?php 
	function fn_HtmlInputField(
		$id, 
		$value, 
		$type, 
		$field_label, 
		$field_help, 
		$OnChange="", 
		$required=false, 
		$submit=false, 
		$pattern=false, 
		$disable=false, 
		$complinput="", 
		$name=""
	) {
		$classlabel = "control-label col-md-3 col-sm-3 col-xs-12"; 
		$classinput = "col-md-6 col-sm-6 col-xs-12";
		$onchange="";
		if ($OnChange!="")
			 $onchange='onBlur="'.$OnChange.'" ';
		if ($name=="")
			$name = $id;
		$return  = '<div class="form-group">'.PHP_EOL;
		$return .= ' 	<label for="'.$id.'" class="' .$classlabel. '">'.fn_HtmlSpanLabel($field_label).'</label>'.PHP_EOL;
		$return .= fn_Help($field_help).PHP_EOL;
		$return .= '	<div class="col-md-6 col-sm-6 col-xs-12">'.PHP_EOL;
		$return .= ' 		<input class="form-control ' . $classinput . '" type="'.$type.'" id="'.$id.'" name="'.$name.'" value="'.$value.'" '.$onchange.$complinput;
		//if ($type=='password')
		//$return.=' class="preview-password" ';
		if ($pattern<>false)
			$return.='pattern="'.$pattern.'" ';
		if ($required==true)
			$return.='required="required" ';
		if ($disable==true)
			$return.='disabled ';
		$return	.='/>'.PHP_EOL;
		$return	.='</div>'.PHP_EOL;
		$return.='</div>'.PHP_EOL;
		return $return;
	}
?>