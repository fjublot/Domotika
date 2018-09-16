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
$(function() {
    var timeout = 2000;
    var refreshInterval = setInterval(function() {
        var random = Math.floor(Math.random() * Math.pow(2, 31));
        $('img#livestream9').attr('src', 'http://192.168.1.30/sf5/?app=ws&page=viewcamproxy2&numero=9&random=' + random); //send a random var to avoid cache
    }, timeout);
 })
 </script>
 


