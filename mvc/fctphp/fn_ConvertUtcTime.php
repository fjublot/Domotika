<?php 
	function fn_ConvertUtcTime($date_time, $to_tz)
	{
		$time_object = new DateTime($date_time, new DateTimeZone("UTC"));
		$time_object->setTimezone(new DateTimeZone($to_tz));
		return $time_object->getTimestamp();
	}
?>