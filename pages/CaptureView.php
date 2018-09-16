<?php
$galerie = new galerie();
$image   = new image();
$galerie->titre_site = "Captures";
$galerie->rep_captures = "captures";
/*** Theme affiché ***/
$galerie->theme = "black";
/*** Type d'URL ***/
// 0 = Aucune réecriture d'url (Ex : index.php?dir=galerie/Paysages)
// 1 = Réecriture via htaaccess (Ex : /galerie-Paysages)
$galerie->turl = "0";
/*** Ordre d'affichage ***/
// 0 = Ordre naturel, dépend du serveur
// 1 = Ordre croissant
// 2 = Ordre décroissant
$galerie->oaff = "1";
/*** Miniatures ***/
// Activer les miniatures (nécessite GDLIB PHP sur le serveur)
// 0 = Pas de miniatures
// 1 = Miniatures actives
$galerie->amin = "1";
// Hauteur (max) miniature (en pixels)
// 150 par défaut
$galerie->hmin = "90";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $galerie->titre; ?></title>
	<?php $galerie->js(); ?>
	<script type="text/javascript" src="js/orientation.js"></script>
	<script type="text/javascript" src="js/ajax.js"></script>
	<link rel="stylesheet" type="text/css" href="css/halo/captures.css" />
	<style type="text/css">
	#images li img {
		max-height:<?php echo $galerie->hmin; ?>px;
	}
	</style>
</head>
<body <?php $galerie->body(); ?> >
	<div id="content">
		<ul id="images">
			<?php $galerie->images("<li>", "</li>"); ?>
		</ul>
	</div>
<?
include($GLOBALS["root_path"] . 'Foot.php');