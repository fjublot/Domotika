<?php
header('content-type: multipart/x-mixed-replace; boundary=--myboundary');
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


while (@ob_end_clean());
$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($curl_handle,CURLOPT_USERPWD, $camera->user.':'.$camera->password);
    $im = curl_exec($ch);
echo $im;
curl_close($ch);
?>
