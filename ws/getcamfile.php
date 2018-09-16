<?php
header('Content-Type: text/xml; charset: UTF-8');
header("Cache-Control: no-cache");
$typecamera = new typecamera($_GET['numero']);
$StatusMsg= 'Lecture paramètre modèle camera '.$typecamera->label;
echo '<camfile>';
echo '<debug>'.$GLOBALS["config"]->general->debug.'</debug>';
echo '<type>'.$typecamera->label.'</type>';
echo '<js>'.$typecamera->js.'</js>';
echo '<inc>'.$typecamera->inc.'</inc>';
echo '<flux>'.utf8_encode(htmlspecialchars($typecamera->flux, ENT_NOQUOTES)).'</flux>';
echo '<image>'.utf8_encode(htmlspecialchars($typecamera->image, ENT_NOQUOTES)).'</image>';
echo '<autorefresh>'.$typecamera->autorefresh.'</autorefresh>';
echo '<message>'.utf8_encode($StatusMsg).'</message>';
echo '</camfile>';
?>