<?php
	foreach(array('ressources/jquery/jquery-last.min.js',/* 'ressources/jquery/jquery-migrate-3.0.1.min.js',*/) as $jsfile) {
		$filename = $jsfile;
		if (file_exists($filename)) {
			$filemtime = filemtime ($filename);
			echo '<script src="' . $filename . '?time=' . $filemtime . '"></script>' . PHP_EOL;
		}
		else
			echo '<!-- ' . $filename . ' introuvable -->' . PHP_EOL;
	}
?>