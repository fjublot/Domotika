<?php 
	function fn_HtmlButtonPicto($id, $value, $field_label, $field_help) {
		$classlabel = "control-label col-md-3 col-sm-3 col-xs-12"; 
		$htmlClassSelect =' previewimage ';  
		$htmlImgPreview = '<img class="preview_icon" alt="preview_icon" id="preview_'.$id.'" src="'.$value.'" />';
		
		$return  = '<div class="form-group">';
		$return .= ' 	<label for="'.$id.'" class="' .$classlabel. '">'.fn_HtmlSpanLabel($field_label).'</label>';
		$return .= fn_Help($field_help);
		$return .= '<input type="hidden" id="'.$id.'" name="'.$id.'" value="'.$value.'">'.PHP_EOL;
		$return .= '	<div class="col-md-6 col-sm-6 col-xs-12">';
		$return .= '	 <button id = "BT_modal_'.$id.'" type="button" class="btn btn-info" >'.ucfirst(fn_GetTranslation('select')).'</button>'; //data-toggle="modal" data-target="#modal_'.$id.'"
		$return .= '	</div>';
		$return .= '	<div class="center col-md-2 col-sm-2 col-xs-12">'.PHP_EOL;
		$return .= 		$htmlImgPreview;
		$return .= '	</div>'.PHP_EOL;
		$return .= '</div>';

		$return .= '<!-- Modal -->';
		$return .= '<div class="modal fade" id="modal_'.$id.'" role="dialog">';
		$return .= '	<div class="modal-dialog modal-lg">';
		$return .= '		<div class="modal-content">';
		$return .= '			<div class="modal-header">';
		$return .= '		  		<button type="button" class="close" data-dismiss="modal">&times;</button>';
		$return .= '		  		<h4 class="modal-title">'.ucfirst(fn_GetTranslation('choosepicture')).'</h4>';
		$return .= '			</div>';
		$return .= '			<div id="modalbody_'.$id.'" class="modal-body">';
		$return .= '			</div>';
		$return .= '			<div class="modal-footer">';
		$return .= '				<span class="selectedimage col-md-6 col-sm-6 col-xs-12">'.Ucfirst(fn_GetTranslation('none')).'</span>';
        $return .= '				<button type="button" class="btn btn-default" data-dismiss="modal">'.Ucfirst(fn_GetTranslation('close')).'</button>';
        $return .= '				<button type="button" class="btn btn-primary" data-idinput="'.$id.'" onClick="javascript:SelectThumbnail(this)">'.Ucfirst(fn_GetTranslation('select')).'</button>';
		$return .= '			</div>';
		$return .= '	  	</div>';
		$return .= '	</div>';
		$return .= '</div>';
		
		return $return;

	}
?>