<?php
/*-----------------------------------------------------------------------------*
* Titre : Main.php                                                            *
*-----------------------------------------------------------------------------*/
?>
 		<form style="display:none">
			<input type="hidden" name="UpdateStatus"  id="UpdateStatus" value="0">
			<input type="hidden" id="flag_stop_ajax" value="0">
		</form>
<?php
	echo fn_HtmlStartPanel(ucfirst(fn_GetTranslation("dashboard")), ucfirst(fn_GetTranslation("controlpanel")), "", "none");
?>
		<div class="x_content">
<?php
		include($GLOBALS["page_inc_path"] . 'pages.php');
?>
		</div>
	</div>
</div>
<?php		
		include($GLOBALS["page_inc_path"] . 'headloadjs.php');

		if ($norefresh == "") {
		?>
			<script type="text/javascript"> 
			jQuery(document).ready(function() {setInterval("UpdateStatus();", 500);});
			</script>
		<?php
		}
		?>