<?php 
	function fn_HtmlDivError($message, $die=false)
	{
		echo "<div style='width:350;margin:auto;text-align:center;font-family:Arial'>
		<span style='font-size:15px;color:red'>".$message."</span>
		</div>";
		if ($die)
			die;
	}
?>