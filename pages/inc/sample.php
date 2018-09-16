<?php
/*-----------------------------------------------------------------------------*
* Titre : sample.php                                                           *
*------------------------------------------------------------------------------*
* Crꧠpar    : Thomas           Le : 30/04/2012       Version : 1.00          *
* Modifi矰ar : XXXXXXXX         Le : XX/XX/XXXX       Version : 1.01          *
*-----------------------------------------------------------------------------*/
require_once($GLOBALS["page_inc_path"] . 'loginverif.php');
header("Cache-Control: no-cache");
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	</head>
	<body>
		<div id="btn_valeur_10"></div>
		<img id="btn_image_10"></img>
		<script type="text/javascript" src="js/ajax.js"></script>
		<script type="text/javascript" src="js/function.js"></script>
		<script type="text/javascript">
			UpdateStatus('filtre=1&btn_id=10');
			setInterval("UpdateStatus('filtre=1&btn_id=10');", 5000);
		</script>
	</body>
</html>