<?php
// Le message
$message = "Line 1\r\nLine 2\r\nLine 3";

// Dans le cas o� nos lignes comportent plus de 70 caract�res, nous les coupons en utilisant wordwrap()
$message = wordwrap($message, 70, "\r\n");

// Envoi du mail
mail('fred@jublot.com', 'Mon Sujet', $message);
?>
