<?php 
	echo PHP_EOL;
	$cssfiles[] = 'ressources/bootstrap/css/bootstrap.min.css';
	$cssfiles[] = 'ressources/bootstrap-toogle/css/bootstrap-toggle.min.css';
	$cssfiles[] = 'ressources/select2/css/select2.css';
	$cssfiles[] = 'ressources/select2/css/select2-bootstrap.css';
	$cssfiles[] = 'ressources/jquery-form-validator/theme-default.min.css';
	$cssfiles[] = 'ressources/confirm/dist/jquery-confirm.min.css';
	$cssfiles[] = 'fonts/css/font-awesome.min.css';
	$cssfiles[] = 'css/animate.min.css';
	$cssfiles[] = 'css/loader.css';
	$cssfiles[] = 'css/menu.css';
	$cssfiles[] = 'css/drag-and-drop.css';
	$cssfiles[] = 'css/tooltips.css';
	$cssfiles[] = 'css/paginate.css';
	$cssfiles[] = 'css/onglets.css';
	$cssfiles[] = 'css/configpage.css';
	$cssfiles[] = 'css/jquery_important.css';
	$cssfiles[] = 'css/IPX800.css';
	$cssfiles[] = 'css/timers.css';
	$cssfiles[] = 'css/scrollbarre.css';
	$cssfiles[] = 'css/dtree.css';
	$cssfiles[] = 'css/camera.css';
	$cssfiles[] = 'css/topbar.css';
	$cssfiles[] = 'css/categories.css';
	$cssfiles[] = 'css/help.css';
	$cssfiles[] = 'css/container.css';
	$cssfiles[] = 'css/progress.css';
	$cssfiles[] = 'css/anim.css';
	$cssfiles[] = 'css/donate.css';
	$cssfiles[] = 'css/buttonrelai.css';
	
	if ($page=="AddImage") {
		$cssfiles[] = 'ressources/dropzone/basic.css';
		$cssfiles[] = 'ressources/dropzone/dropzone.css';
	}

	if (in_array($page, array("Trace","Histo")))
		$cssfiles[] = 'ressources/datatables/css/jquery.dataTables.min.css';

	if ($page=='Setup') {
		$cssfiles[] = 'css/wizardcustom.css';
		$cssfiles[] = 'css/smart_wizard.css';
		$cssfiles[] = 'css/commun/ipx-valid.css';
	}
	
	$cssfiles[] = 'css/custom.css';

	foreach ($cssfiles as $cssfile) {
		$filename = $cssfile;	
		if (file_exists($filename)) {
			$filemtime = filemtime ($filename);
			echo '<link rel="stylesheet" type="text/css" media="screen" href="' . $filename . '?time=' . $filemtime . '">' . PHP_EOL;
		}
		else {
			echo '<!-- ERREUR : ' . $filename . ' manquant -->' . PHP_EOL;
		}	
	}

?>