<?php
header('Content-Type: text/xml; charset: UTF-8');
header("Cache-Control: no-cache");
if ( isset($_REQUEST['ajax']) )
{
	if ( $_REQUEST['ajax'] == 0 )
	{
		$_SESSION['Ajax'] = false;
		$status = "Ko";
	}
	else
	{
		$_SESSION['Ajax'] = true;
		$status = "Ok";
	}
}
else
{
	$_SESSION['ajax'] = true;
	$status = "Ok";
}
?>
<document>
<element status="<?php echo $status;?>"></element>
</document>