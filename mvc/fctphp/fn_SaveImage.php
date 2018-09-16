<?php 
	function fn_SaveImage($img,$fullpath)
	//Sauvegarde un fichier image récupéré par url
	{
		$ch = curl_init ($img);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
		$rawdata=curl_exec($ch);
		curl_close ($ch);
		if(file_exists($fullpath))
		{
			chmod($fullpath, 755);
			unlink($fullpath);
		}
		$fp = fopen($fullpath,'x');
		fwrite($fp, $rawdata);
		fclose($fp);
	}
?>
