<?php
		echo fn_HtmlStartPanel('Help', 'fr', '', '');
?>		
		<div class="x_content">
			<div class="container">
				<div class="panel-group" id="accordion">

		<?php
	if ( isset($_REQUEST["lang"]) )
		$_SESSION['lang'] = $_REQUEST["lang"];
	 // Instanciation de la classe DomDocument : création d'un nouvel objet
	echo fn_ParsageHelp('help/'.$_SESSION['lang'].'/sommaire.help');
?>
				</div>
			</div>
		</div>
<?php
	include($GLOBALS["page_inc_path"] . 'headloadjs.php');
?>

