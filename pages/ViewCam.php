<?php
/*-----------------------------------------------------------------------------*
   * Titre : index.php                                                            *
   *-----------------------------------------------------------------------------*/
?>
	<div data-role="content">
	   
	<?php
	$interval=300;
	if (isset($_REQUEST["numero"])) {
		$cameralist = ($_REQUEST["numero"]);
		if (count(explode(',', $cameralist))>1) 
		{
			$OneOrMulti='Multi';
			$interval=count(explode(',', $cameralist))*$interval;
		}
		else
		{
			$OneOrMulti='One';
			$interval=$interval;
		}
    
		foreach(explode(',', $cameralist) as $value ) {
		?>
			<div class="ContainerLiveView<?php echo $OneOrMulti; ?>">
			<div id="LiveViewTitle<?php echo $value;?>"></div>
			<?php 
			echo '<img id="livestream' . $value . '" alt="livestream' . $value . '" src="images/commun/empty.gif" />';?>
			</div>
			<?php
		}
    }
	include($GLOBALS["page_inc_path"] . 'headloadjs.php');
    ?>
    

<script type="text/javascript">
	jQuery(document).ready(function() {
		<?php
			if ($numero!="") {
				$cameralist = ($numero);
				foreach(explode(',', $cameralist) as $value ) {
					$camera = new camera($value);
					echo $camera->streamview();
					echo $camera->updatestream();
				}
			};
		?>
	});
</script>
</div>


