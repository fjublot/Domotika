<?php
	global $db;
	$html='';
	$alerts = $db->getLastAlerts();
	foreach ($alerts as $alert) {
		$model = fn_GetModel("carte", $alert['carte_id']);
		$currentcarte = new $model($alert['carte_id']);
 		$html .= '<li><a>';
		$html .= '	<span class="image">';
		$html .= '		<img src="images/cartes/'.$currentcarte->model.'.png" alt="Profile Image" />';
		$html .= '	</span>';
		$html .= '	<span>';
		$html .= '	<span>' . $currentcarte->label .'</span>';
		$html .= '	<span class="time2">' . $alert['timeutc'].'</span>';
		$html .= '</span>';
		$html .= '<span class="message">';
		$html .= $alert['message'];
		$html .= '</span>';
		$html .= '</a></li>';
	}
	echo $html;
?>
