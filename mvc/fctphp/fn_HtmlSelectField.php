<?php 
	function fn_HtmlSelectField(
		$id, 
		$field_label, 
		$field_help, 
		$OnChange = "", 
		$multiple=false, 
		$preview_div=false, 
		$disabled=false, 
		$classlabel="", 
		$classselect="", 
		$name=""
	) { 
		if ($name=='')
			$name=$id;

		if ($classlabel=='')
			$classlabel="control-label col-md-3 col-sm-3 col-xs-12";

		if ($classselect=="")
			$classselect="col-md-6 col-sm-6 col-xs-12";

		if ($multiple==true) {
			$htmlClassSelect=' select2_multiple ';
			$htmlname = ' name="'.$name.'[]" ';
			$htmlMultiple= ' multiple ';
		}
		else {
			$htmlClassSelect='';
			$htmlname = ' name="'.$name.'" ';
			$htmlMultiple = ' size="1" ';
		}

		$htmlDisabled='';
		if ($disabled==true) { 
			$htmlDisabled = ' disabled="disabled" ';
			$htmlMultiple = '';
			$htmlname = "";
		}
	
		$htmlOnChange = "";
		if ($OnChange != "")
			$htmlOnChange = ' onchange="'.$OnChange.'" ';
		
		$htmlImgPreview = '';
		if ($preview_div==true) {   
			$htmlClassSelect.=' previewimage ';  
			$htmlImgPreview = '<img class="preview_icon" alt="preview_icon" id="preview_'.$id.'" src="images/commun/empty.gif" />';
		}
		$return  = '<div class="form-group">'.PHP_EOL;
		$return .= '	<label for="'.$id.'" class="control-label ' .$classlabel. '">'.fn_HtmlSpanLabel($field_label).'</label>'.PHP_EOL;
		$return .= fn_Help($field_help);
		$return .= '	<div class="' . $classselect . '">'.PHP_EOL;
		$return .= '		<SELECT id="'.$id.'" class="form-control select2 '.$htmlClassSelect .'" ';
		$return .= $htmlDisabled . $htmlname . $htmlOnChange . $htmlMultiple . '></SELECT>'.PHP_EOL;
		$return .= '</div>'.PHP_EOL;
		$return .= '<div class="center col-md-2 col-sm-2 col-xs-12">'.PHP_EOL;
		$return .= $htmlImgPreview;
		$return .= '</div>'.PHP_EOL;
		$return .= '</div>'.PHP_EOL;
		return $return;
	}
?>