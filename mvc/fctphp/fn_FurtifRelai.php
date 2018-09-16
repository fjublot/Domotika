<?php
	function fn_FurtifRelai($relai_id, $value) {
		$relai = new relai($relai_id);
		$model = fn_GetModel("carte", $relai->carteid);
		$carte = new $model($relai->carteid);

		$carte->FurtifRelai($relai->no, $value);
	}
?>