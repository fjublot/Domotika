<?php
// --------------------------------------------------------------------------------------------------
// fonction de redimensionnement A L'AFFICHAGE
// --------------------------------------------------------------------------------------------------
// La FONCTION : fn_PictureDisplay($Wmax, $Hmax, $img_Src)
// Les paramètres :
// - $Wmax : LARGEUR maxi finale ----> ou 0 : largeur libre
// - $Hmax : HAUTEUR maxi finale ----> ou 0 : hauteur libre
// - $img_Src : NOM de l image Source
// --------------------------------------------------------------------------------------------------
// UTILISATION (exemple) :
// <img src="repimg/monimage.jpg" <?php fct_affich_image(100, 100, 'repimg/monimage.jpg') 
// --------------------------------------------------------------------------------------------------
	function fn_PictureDisplay($Wmax, $Hmax, $img_Src) {
	 // ------------------------------------------------------------------
	   // Lit les dimensions de l'image
	   $sizeimg = GetImageSize($img_Src);  
	   $Src_W = $sizeimg[0]; // largeur
	   $Src_H = $sizeimg[1]; // hauteur
	 // ------------------------------------------------------------------
	   // Teste les dimensions tenant dans la zone
	   $test_H = round(($Wmax / $Src_W) * $Src_H);
	   $test_W = round(($Hmax / $Src_H) * $Src_W);
	 // ------------------------------------------------------------------
	   // Si Height final non précisé (0)
	   if(!$Hmax) $Hmax = $test_H;
	   // Sinon si Width final non précisé (0)
	   elseif (!$Wmax) $Wmax = $test_W;
	   // Sinon teste quel redimensionnement tient dans la zone
	   elseif ($test_H>$Hmax) $Wmax = $test_W;
	   else $Hmax = $test_H;
	 // ------------------------------------------------------------------
	   // (procédure : ne retourne aucune valeur mais ...)
	   // AFFICHE les dimensions optimales
	   echo 'width='.$Wmax.' height='.$Hmax;
	}
?>