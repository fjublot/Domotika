<?php
/**
* Convert BR tags to newlines and carriage returns.
*
* @param string The string to convert
* @param string The string to use as line separator
* @return string The converted string
*/
	function fn_br2nl ( $string, $separator = PHP_EOL ) {
		$separator = in_array($separator, array("\n", "\r", "\r\n", "\n\r", chr(30), chr(155), PHP_EOL)) ? $separator : PHP_EOL;  // Checks if provided $separator is valid.
		return preg_replace('/\<br(\s*)?\/?\>/i', $separator, $string);
	}

	function fn_nl2br($string, $separator = PHP_EOL) { 
		$separator = in_array($separator, array("\n", "\r", "\r\n", "\n\r", chr(30), chr(155), PHP_EOL)) ? $separator : PHP_EOL;  // Checks if provided $separator is valid.
		return str_replace(array("\r\n", "\r", "\n"), $separator, $string); 
	} 
?>