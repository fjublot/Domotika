<?php 
	function fn_HtmlHiddenField($field, $value, $noid=false)
	{
		$return = '<input type="hidden" ';
		if ($noid==false)
		$return .= 'id="'.$field.'" ';
		$return .= 'name="'.$field.'" value="'.$value.'">'.PHP_EOL;
		return $return;
	}
?>