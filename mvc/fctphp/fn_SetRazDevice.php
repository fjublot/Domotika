<?php
	function fn_SetRazDevice($razdevice_id, $value) {
		$razdevice = new razdevice($razdevice_id);
		$model = fn_GetModel("carte", $razdevice->carteid);
		$carte = new $model($razdevice->carteid);
		return $carte->SetRelai($razdevice_id, $value);
	}
?>