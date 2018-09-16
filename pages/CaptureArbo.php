<?php
include ($GLOBALS["page_inc_path"] .'head.php');
include ($GLOBALS["page_inc_path"] .'topbar.php');
echo '<script type="text/javascript">
    	    UpdateTitle("'.fn_GetTranslation('capture_history').'");
	    </script>';
echo '<script type="text/javascript">
	function View(file)
	{
		window.frames.traceview.location.href =file;
	}
</script>';
$prefix=isset($_GET['prefix'])?$_GET['prefix']:'*';
?>
<span id="tracetree">
<a href="#" onclick="Tree1.openAll()"><?php echo fn_GetTranslation('open_all'); ?></a>&nbsp;
<a href="#" onclick="Tree1.closeAll()"><?php echo fn_GetTranslation('close_all'); ?></a></p>
<script type="text/javascript">
		Tree1 = new dTree('Tree1');
		Tree1.add(0,-1,'<?php echo fn_GetTranslation('captures'); ?>');
<?php
// Configuration
$dossier = 'captures';
$noid = 0;
function AddSubEntry($noid, $dossier)
{
	$parent_nid = $noid;
	$ouverture = opendir($dossier);
	$contenu = array();
	while ($fichier = readdir($ouverture))
	{
		if ( $fichier != "." && $fichier != ".." && $fichier != "thumb" )
		{
			$contenu[] = $fichier;
		}
	}
	sort($contenu);
	foreach ($contenu as $fichier)
	{
		if ( is_dir($dossier."/".$fichier) )
		{
			$noid++;
			echo "Tree1.add(".$noid.",".$parent_nid.",'".$fichier."','javascript:View(\'?app=Mn&page=CaptureView?dir=".$dossier."/".$fichier."\')');".PHP_EOL;
			$noid = AddSubEntry($noid, $dossier."/".$fichier);
		}
	}
	return $noid;
}
$noid = AddSubEntry($noid, $dossier);
?>
document.write (Tree1);
</script>
</span>
<iframe name='traceview' id='traceview' width=70% src="" height="700"></iframe>
<span id="tracelog">
</span>