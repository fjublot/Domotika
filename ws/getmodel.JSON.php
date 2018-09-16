<?php		
	header('Content-Type: application/json; charset=utf-8');
	$result[]    = array('model' => fn_GetModel($class, $numero));
	echo json_encode($result);
?>