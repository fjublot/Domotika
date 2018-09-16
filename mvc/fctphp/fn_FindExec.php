<?php 
function fn_FindExec($file)
	{
		$filename = false;
		$old_error_niveau = error_reporting(E_ERROR);
		if ( isset($_SERVER['PATH']) )
			$path = $_SERVER['PATH'];
		elseif ( isset($_SERVER['Path']) )
			$path = $_SERVER['Path'];

		if ( preg_match("/;/", $path) )
			$lpath = preg_split("/[;]+/", $path);
		else
			$lpath = preg_split("/[:]+/", $path);
		
		foreach ($lpath as $dir )
		{
			if ( substr($dir, -1) != DIRECTORY_SEPARATOR )
			$dir .= DIRECTORY_SEPARATOR;
			
			if ( isset($_SERVER['PATHEXT']) )
			{
				foreach (preg_split("/[;:]/", $_SERVER['PATHEXT']) as $ext )
				{
					if ( file_exists($dir.$file.$ext) )
					{
						$filename = $dir.$file.$ext;
						break 2;
					}
				}
			}
			else
			{
				if ( file_exists($dir.$file) )
				{
					$filename = $dir.$file;
					break;
				}
			}
		}
		error_reporting($old_error_niveau);
		return $filename;
	}
?>