<?php 
	function fn_MergeXmlChild(&$base, $add)
	{
		foreach ($add->children() as $child)
		{
			fn_MergeXml($base, $child);
		}
	}
?>