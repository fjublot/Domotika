			<!-- foot.php -->
			</div> <!-- /page content -->
			</div> <!-- /main container -->
			</div> <!-- /container body -->
			
			<script type="text/javascript"> 
				$(window).on("load", function (e) {
					<?php
					if (isset($GLOBALS["config"]->users)) {
						echo $menu->jsOnClick();
					?>
						updateOrientation();
						startAnimationTime();
					<?php
						if ($page=="Main" && $norefresh!="1")
							echo "startUpdateLastAlerts();";
						else {
							if ($page != "Setup")
							echo "AjaxLoadLastAlert('menu1');";
						}
					}
					?>
					$(".loader").fadeOut("500");
					$(".body").fadeIn("500");
					<?php if ($page == "Main") { ?>
					$('.ajaxapiaction').click(function(){
						//alert('<?php echo isset($_SESSION["ApiKey"])?$_SESSION["ApiKey"]:""?>');
						console.log("AjaxApiAction('<?php echo isset($_SESSION["ApiKey"])?$_SESSION["ApiKey"]:""?>', '" + $(this).data('class') + "', '" + $(this).data('numero') + "', '" + $(this).attr('data-command') + "', '" + $(this).data('ctrbtn')+"');");
						AjaxApiAction('<?php echo isset($_SESSION["ApiKey"])?$_SESSION["ApiKey"]:""?>', $(this).data('class'), $(this).data('numero'), $(this).attr('data-command'), $(this).data('ctrbtn'));
					});
					<?php } ?>

<?php

				if (isset($GLOBALS["config"]->users))
					echo $menu->jsDisplay($page);

?>
				})
			</script>
	</body>
</html>
<!-- /foot.php -->