<?php 
	function fn_HtmlSpanLabel($field_label)
	{
		$html ='<span class="span-label">';
		$html.=ucfirst(fn_GetTranslation($field_label));
		$html.='</span>';
		return $html;
	}
?>