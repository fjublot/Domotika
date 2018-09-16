<?php 
	function fn_GetListHelpFile($prefix, $jsonorarray) {
		$result = array();
		$handle = opendir($GLOBALS["root_path"]."help/".$_SESSION['lang']);
			if ($handle) {
				$files = array();
				while (false !== ($file = readdir($handle))) {
					if ( preg_match("/".$prefix.".(.*).help/", $file) ) {
						array_push($files, $file);
					}
				}
				closedir($handle);
				sort($files);
				foreach($files as $file) {
					if ( preg_match("/".$prefix.".(.*).help/", $file, $skip) ) {
						$data = simplexml_load_file("help/".$_SESSION['lang']."/".$file);
						$result[] = array(
							'id' => utf8_decode($data->prototype),
							'text' => utf8_decode($data->titre)
						);
					}
				}
			}
		if ($jsonorarray=="json")
			return json_encode($result);
		else
			return $result;
	}
?>