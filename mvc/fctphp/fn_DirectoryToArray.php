<?php 
	function fn_DirectoryToArray($directory, $recursive = true, $listDirs = false, $listFiles = true, $exclude = '')
	{
		$arrayItems = array();
		$skipByExclude = false;
		$handle = opendir($directory);
		if ($handle)
		{
			while (false !== ($file = readdir($handle)))
			{
				preg_match("/(^(([\.]){1,2})$|(\.(svn|git|md))|(Thumbs\.db|\.DS_STORE))$/iu", $file, $skip);
				if($exclude)
				{
					preg_match($exclude, $file, $skipByExclude);
				}
				if (!$skip && !$skipByExclude)
				{
					if (is_dir($directory. DIRECTORY_SEPARATOR . $file))
					{
						if($recursive)
						{
							$arrayItems = array_merge($arrayItems, fn_DirectoryToArray($directory. DIRECTORY_SEPARATOR . $file, $recursive, $listDirs, $listFiles, $exclude));
						}
						if($listDirs)
						{
							$file = $directory . DIRECTORY_SEPARATOR . $file;
							$arrayItems[] = $file;
						}
					}
					else
					{
						if($listFiles)
						{
							$file = $directory . DIRECTORY_SEPARATOR . $file;
							$arrayItems[] = $file;
						}
					}
				}
			}
			closedir($handle);
		}
		return $arrayItems;
	}
?>