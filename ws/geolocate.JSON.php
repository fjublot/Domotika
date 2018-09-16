<?php
	header('Content-Type: application/json; charset=utf-8');
	$geoplugin = new geoPlugin();
	// If we wanted to change the base currency, we would uncomment the following line
	// $geoplugin->currency = 'EUR';
	 
	$geoplugin->locate($_REQUEST["ip"]);
	 
	$results[] = array ( 
					"ip" 				=> $geoplugin->ip,
					"city" 				=> $geoplugin->city,
					"region" 			=> $geoplugin->region,
					"areacode" 			=> $geoplugin->areaCode,
					"dmacode" 			=> $geoplugin->dmaCode,
					"countryname" 		=> $geoplugin->countryName,
					"countrycode" 		=> $geoplugin->countryCode,
					"longitude" 		=> $geoplugin->longitude,
					"latitude" 			=> $geoplugin->latitude,
					"currencycode" 		=> $geoplugin->currencyCode,
					"currencysymbol" 	=> $geoplugin->currencySymbol,
					"exchangerate" 		=> $geoplugin->currencyConverter
				);
	

	echo json_encode($results);
?>