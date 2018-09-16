<?php
	function fn_SetRelai($relai_id, $value) {
		$relai = new relai($relai_id);
		$model = fn_GetModel("carte", $relai->carteid);
		$carte = new $model($relai->carteid);
		$carte->SetRelai($relai->no, $value);     
	}
?>