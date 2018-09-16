<?php
// traitement de l'url  pour création de la commande
$xpath='//users/user[@numero='.$userid.']';
$user = $GLOBALS["config"]->xpath($xpath);


?>
		
	<div class="x_content">
	<form class="form-horizontal form-label-left">
<?php 
	/*if ( $numero !="") {
		$model = fn_GetModel("carte", $carteid);     
		$current = new $model($carteid);
		foreach($current->subclass as $class) {
			echo fn_HtmlStartPanel($class, $class, "");
			echo '<div class="x_content">';
			echo '<form class="form-horizontal form-label-left">';
			echo '<div id="'.$class.'" class="list-content group">';
			
			$xpath = "//".$class."[carteid='".$numero."']";
			echo '<!-- '.$xpath.'-->'.PHP_EOL;
			$nodes = $GLOBALS["config"]->xpath($xpath);

			foreach($nodes as $info) {
				echo '<!-- '.$class.$info->attributes()->numero.'-->'.PHP_EOL;
				$sub_current = new $class($info->attributes()->numero, $info);
				echo fn_HtmlBinaryAuthField($class,$sub_current->numero, fn_GetAuth($userid, $class, $sub_current->numero), $sub_current->label, $sub_current->label);
			}
			echo "</div></div></div>";
		}
	}
	else*/ {
		echo fn_HtmlStartPanel(ucfirst(fn_GetTranslation($class.'s')),"authorizations_management", "");
		echo '<div class="x_content">';
		echo '<form class="form-horizontal form-label-left">';
		if ( isset($GLOBALS["config"]->{$class."s"}) ) {
			foreach($GLOBALS["config"]->{$class."s"}->{$class} as $info) {
				//if ( isset($info->carteid))
				//	continue;
				$model = fn_GetModel($class, $info->attributes()->numero);     
				$current = new $model($info->attributes()->numero, $info);
				echo '<!-- '.$model.'('.$info->attributes()->numero. ') Auth : ' . fn_GetAuth($userid, $class, $current->numero) . '-->';
				echo fn_HtmlBinaryAuthField($class, $current->numero, fn_GetAuth($userid, $class, $current->numero), $current->label, $current->label);
			}
		}
		echo '</div></div>';
	}
?>
</form>
	<?php
		include($GLOBALS["page_inc_path"] . 'headloadjs.php');
	?>
	<script type="text/javascript">
	jQuery(document).ready(function () {     
		jQuery('input[type="checkbox"].binaryauth').change(function() {
			if (jQuery(this).is(':checked')){ 
				valeur = 'on';
			} 
			else { 
				valeur = 'off';
			};
			$.ajax({
				type: 'POST',
				url: '?app=Ws&page=setauth.JSON&userid=<?php echo $userid; ?>&class='+jQuery(this).data("classname")+'&numero='+jQuery(this).data("numero")+'&value='+valeur,
				contentType: "application/json; charset=utf-8",			
				success: function(json){
					$.each(json, function(j, item) {
						new PNotify({
							title: 'Information',
							text: item.message,
							type: 'info',
							icon: 'picon picon-document-encrypt'
						});

					});
				} 
			});
		});
	});		
	</script>

	</body>
</html>
