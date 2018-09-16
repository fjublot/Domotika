<?php
	$jsfiles = array(
		'ressources/bootstrap/js/bootstrap.min.js',
		'ressources/notify/pnotify.core.js',
		'ressources/notify/pnotify.buttons.js',
		'ressources/notify/pnotify.nonblock.js',
		'ressources/bootstrap-toogle/js/bootstrap-toggle.min.js',
		'ressources/jquery-form-validator/jquery.form-validator.min.js',
		'ressources/select2/js/select2.min.js',
		'ressources/confirm/dist/jquery-confirm.min.js',
		'ressources/bootbox/bootbox.js',
		'ressources/sortable/Sortable.js',
		'ressources/clipboard/clipboard.min.js',
		'ressources/justgage/raphael-2.1.4.min.js',
		'ressources/justgage/justgage.js',
		'js/ajax.js',
		'js/drag-and-drop.js',
		'js/help.js',
		'js/graph.js',
		'js/dtree.js',
		'js/orientation.js',
		'js/function.js',
		'js/tooltips.js',
		'js/onglets.js',
		'js/custom.js'
	);

	if ($page=="AddImage")
		$jsfiles[] = 'ressources/dropzone/dropzone.min.js';
	
	if (in_array($page, array("Histo"))) 
		$jsfiles[] = 'ressources/datatables/js/jquery.dataTables.js';

	if (in_array($page, array("Trace"))) {
		$jsfiles[] = 'ressources/datatables/js/jquery.dataTables.js';
		$jsfiles[] = 'ressources/popper/popper.min.js';
	}

	if (in_array($page, array("Setup","UpdateConfig"))) {
		$jsfiles[] = 'ressources/wizard/jquery.smartWizard.js';	
	}
	foreach( $jsfiles as $jsfile) {			
		$filename = $jsfile;
		if (file_exists($filename)) {
			$filemtime = filemtime ($filename);
			echo '<script src="' . $filename . '?time=' . $filemtime . '"></script>' . PHP_EOL;
		}
		else 
			echo '<!-- fichier ' . $filename . ' inexistant -->' . PHP_EOL;
	}


	echo '<script>PNotify.prototype.options.styling = "fontawesome";</script>';
?>

