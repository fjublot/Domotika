<?php 
	function fn_HtmlStartFieldset($legend, $id="", $collapse=false)
	{	$attrid = "";
		if ($id!="")
			$attrid = ' id="'.$id.'"';
		if ($collapse == true) 
			$checked = '';
		else
			$checked = 'checked';
		$return  = '<div class="row">';
		$return .= '     <div class="panel panel-default"' . $attrid . '>';
		$return .= '          <input class="collapse-open panel-heading" type="checkbox" id="panel-' . $legend . '"' . $checked . ' >';
		$return .= '          <label class="panel-legend panel-heading" for="panel-' . $legend .'">' . $legend .'</label>';
		$return .= '          <div class="panel-body">';
		return $return;
	}
?>