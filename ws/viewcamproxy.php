<?php
$camera = new camera($_REQUEST["numero"]);
$host=$camera->host.":".$camera->port;
$image  = utf8_decode($camera->image);
$option = utf8_decode($camera->option);
$option = str_replace('*width*',$camera->width,$option);
$option = str_replace('*height*',$camera->height,$option);
$option = str_replace('*canal*',$camera->canal,$option);
$option = str_replace('*quality*',$camera->quality,$option);
$option = str_replace('&amp;','&',$option);


$rand = rand(1000,9999);
$url = $host.$image.'?'.$option.'&random='.$rand; // remember there needs to be a ? between the URL and the random number. Don't remove the question mark.

$curl_handle=curl_init();
curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,true);  
curl_setopt($curl_handle,CURLOPT_URL,$url);
curl_setopt($curl_handle,CURLOPT_USERPWD, $camera->user.':'.$camera->password);
curl_setopt($curl_handle,CURLOPT_TIMEOUT_MS,1500);
curl_setopt($curl_handle,CURLOPT_NOSIGNAL, 1);
curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT_MS,1500);
$buffer = curl_exec($curl_handle);
curl_close($curl_handle);

if (empty($buffer)) $buffer="";

print base64_encode($buffer);

?>