<!DOCTYPE html>
<html lang="fr">
	<head>
    <!-- Force latest IE rendering engine or ChromeFrame if installed -->
    <!--[if IE]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
	<?php
		include($GLOBALS["page_inc_path"] . 'headmeta.php');
		include($GLOBALS["page_inc_path"] . 'headloadcss.php');
		include($GLOBALS["page_inc_path"] . 'headjquery.php');	
	?>
	</head>
	<?php
	if (isset($GLOBALS["config"]->general->debug) && $GLOBALS["config"]->general->debug == 1)
		echo "<!-- Debug php Actif -->";
	?>
	<body>
		<div class="loader">
			<div class="spinner">
			  <div class="spinner__item1"></div>
			  <div class="spinner__item2"></div>
			  <div class="spinner__item3"></div>
			  <div class="spinner__item4"></div>
			</div>
		</div>
 
		<div class="container body" style="display:none;">
			<!--<div class="main_container">-->
