<?php 
	function fn_RmDirRec($dir)
	{
		$files = array_diff(fn_Scandir($dir), array('.','..'));
		foreach ($files as $file)
		{
			if ( is_dir($dir."/".$file) )
				fn_RmDirRec($dir."/".$file);
			else
				unlink($dir."/".$file);
		}
		return rmdir($dir);
	}
?>