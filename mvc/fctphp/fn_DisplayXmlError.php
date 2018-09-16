<?php 
	function fn_DisplayXmlError($error, $xml)
	{
		$return = "";
		if ( isset($error->line) )
			$return  .= "Ligne : ".$error->line.PHP_EOL;
		if ( isset($error->column) )
			$return  .= "Char : ".$error->column.PHP_EOL;
		if ( isset($error->line) && isset($error->column) && isset($xml[$error->line - 1]) )
		{
			$return .= "Text ".$xml[$error->line - 1].PHP_EOL;
			$return .= "     ".str_repeat('-', $error->column).PHP_EOL;
		}
		
		switch ($error->level)
		{
			case LIBXML_ERR_WARNING:
				$return .= "Warning ".$error->code.PHP_EOL;
				break;
			case LIBXML_ERR_ERROR:
				$return .= "Error ".$error->code.PHP_EOL;
				break;
			case LIBXML_ERR_FATAL:
				$return .= "Fatal Error ".$error->code.PHP_EOL;
				break;
		}
		
		$return .= trim($error->message);
		
		return $return;
	}
?>