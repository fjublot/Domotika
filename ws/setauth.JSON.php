<?php
	header('Content-Type: application/json; charset=utf-8');
	// traitement de l'url  pour création de la commande
	header("Cache-Control: no-cache");
	if($userid!="" && $class!="" && $numero!="" && $value!="" ) {
		fn_SetAuth($userid, $class, $numero, $value);
		$result[] = array(
			'user' 		=> $userid,
			'class' 	=> $class,
			'numero' 	=> $numero,
			'message' 	=> 'User['.$userid.'] - '.$class.'['. $numero.'] -> '.$value.' - Mise à jour effecuée'
			);
		echo json_encode($result);
	}
?>

