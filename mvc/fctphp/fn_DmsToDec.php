<?php 
	function fn_DmsToDec($dms, $longlat)
	{
		if($longlat == 'lattitude')
		{
			$decalage = 2;
		}
		if($longlat == 'longitude')
		{
			$decalage = 3;
		}
		$data = explode(".", $dms);
		$min = substr($data[0], -$decalage).".".$data[1];
		$deg = substr($data[0], 0, -$decalage);

		return $deg+((($min*60))/3600);
	}
?>