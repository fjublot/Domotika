<?php

if(preg_match("/image.php/i", $_SERVER['PHP_SELF'])) { echo "Vous ne pouvez pas acceder directement &agrave; cette page !"; exit(); }

if(!file_exists("thumb/$lien")) {				
	@image::ResizeImage($lien, "thumb/".$lien, 0, $this->hmin);
} else {
	$dimm = getimagesize("thumb/$lien");
	if($dimm[1] != $this->hmin) {
		@image::ResizeImage($lien, "thumb/".$lien, 0, $this->hmin);
	}
}

echo "<img src=\"thumb/$lien\" />";

?>