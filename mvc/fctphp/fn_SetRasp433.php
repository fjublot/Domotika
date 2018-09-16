<?php 
	function fn_SetRasp433($rasp433_id, $value)
	{
		$rasp433 = new rasp433($rasp433_id);
		$model = fn_GetModel("carte", $rasp433->carteid);
		$carte = new $model($rasp433->carteid);
	  if ($value=="1")
	  {  
		$value="on";
	  }
	  else
	  {
		 $value="off";
		}
	  $carte->fn_SetRasp433($rasp433_id, $value);        
	}
?>