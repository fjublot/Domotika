<?php
	function fn_GetModel($class, $numero, $defaultmodel="") {
		$model=$defaultmodel;
		if ($defaultmodel=="") {
			$xpath = '//'.$class.'s/'.$class.'[@numero="'.$numero.'"]';
			$model = fn_GetByXpath($xpath,'bal','model');
		}
		if (class_exists($class.$model))
			if ($class=='carte' && $numero=='')
			    return $class;
			else
			    return $class.$model;
		else
			return $class;
	}
?>