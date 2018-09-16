<?php
	header('Content-Type: application/json; charset=utf-8');
	$ds         = DIRECTORY_SEPARATOR;  
	$targetPath = $GLOBALS["root_path"] . 'config' . $ds . 'images' . $ds ;  
	
	if (!empty($_FILES)) 
	{
		$tempFile = $_FILES['file']['tmp_name'];            
		$targetFile =  $targetPath. $_FILES['file']['name'];  
		$status = move_uploaded_file($tempFile,$targetFile);
		$redimstatus = fn_PictureResize(128,128,'','','config/images/',$_FILES['file']['name']);
		$result[] = array(
			'status'   => $status,
			'filename' => $_FILES['file']['name'], 
			'path'     => $targetFile,
			'redim'    => $redimstatus);
			
		echo json_encode($result);
	}
	else 
	{                                                           
		$result  = array();
		$files = scandir($targetPath);                
		//$files = glob($targetPath.'*.{jpeg,jpg,gif,png}', GLOB_BRACE);
		if ( false!==$files ) {
			foreach ( $files as $file ) {
				if ( '.'!=$file && '..'!=$file) { 
					$ext = substr($file, strrpos($file, '.') + 1);
					if(in_array(strtoupper($ext), array("JPG","JPEG","PNG","GIF"))) {
						$obj['name'] = $file;
						$obj['size'] = filesize($targetPath.$ds.$file);
						$result[] = $obj;
					}
				}
			}
		}
		echo json_encode($result);
}
?>