<?php 
	function fn_PushTo($message, $numero = "") {
		$code = true;
		$text = "Pas de configuration push";
		
		if ( isset($GLOBALS["config"]->pushtos) ) {    
			foreach($GLOBALS["config"]->pushtos->pushto as $info) {
				$pushto = new pushto((string)$info->attributes()->numero, $info);
				if (is_array($numero)) {
					foreach($numero as $compte_pushto) { 
						if ( $compte_pushto == $pushto->numero && $pushto->actif == 'on') {
							list($current_code, $current_text) = $pushto->send_message($message);
							if ( $current_code == false )
								$code = false;
								$text = $pushto->type.'('.$pushto->label.')'.'->'.$current_text.' - ';
						}
					}
				}
				else {
					if ( ($numero == $pushto->numero || $numero == "" ) && $pushto->actif == 'on') {
						list($current_code, $current_text) = $pushto->send_message($message);
						if ( $current_code == false )
							$code = false;
						$text = $pushto->type.'('.$pushto->label.')'.'->'.$current_text.' - ';
					}
				}
			}
		}
		return array($code, $text);
	}
?>