<?php
/*----------------------------------------------------------------*
* Titre : Add.php                                                 *
* Programme permettant de maintenir les objets                    *
*----------------------------------------------------------------*/
	$msg = 	'<!-- class : "' . $class . '" - numero : "' . $numero . '" - defautlmodel : "' . $model .  '" -->'.PHP_EOL;
	echo $msg;
	$model = fn_GetModel($class, $numero, $model);
	$msg = '<!-- model : '.$model .  ' -->'.PHP_EOL;
	echo $msg;
	$current = new $model($numero);
	echo '<!-- '.$current->label.'-->';
	echo $current->form($page);
	if ( method_exists($current, "advance_form") )
  		echo $current->advance_form();
		?>
			<div class="clearfix"></div>
   			<div class="ln_solid"></div>
			<!-- Zone de boutons -->
			<div class="form-group">
				<div id="input-btns" class="col-md-12 col-sm-12 col-xs-12">
					<?php if ($page!="Setup") {?>
						<button class="btn btn-primary col-sm-2 col-xs-12 pull-right" type="submit" id="BT_Envoyer" name="BT_Envoyer" onclick="jQuery('#action').val(this.id);"><?php echo fn_GetTranslation('send');   ?></button>
						<button class="btn btn-dark col-sm-2 col-xs-12 pull-right" id="BT_Annuler" type="submit" name="BT_Annuler" onclick="jQuery('#action').val(this.id);"><?php echo fn_GetTranslation('cancel'); ?></button>
						<button class="btn btn-danger hidden col-sm-2 col-xs-12 pull-right" type="submit" id="BT_Supprimer" name="BT_Supprimer" onclick="jQuery('#action').val(this.id);"><?php echo fn_GetTranslation('delete'); ?></button>
					<?php
					}
					if (isset($_REQUEST["numero"]) && in_array($_REQUEST["class"], array("relai","btn","cnt","an","variable"))) {
					?>
						<button class="btn btn-primary col-sm-2 col-xs-12 pull-right" type="button" id="BT_Graph" name="BT_Graph"  data-theme="e" onclick="javascript:GetXML('?app=Ws&amp;page=creategraph&amp;class=<?php echo $_REQUEST["class"]; ?>&amp;numero=<?php echo $_REQUEST["numero"]; ?>', AjaxUpdateMessage, '', true);"><?php echo fn_GetTranslation('create_graph'); ?></button>
 					<?php
					}
					?>
				</div>
			</div>
			<!-- /Zone de boutons -->
		</form>
		
	</div><!-- /x-content -->
	</div><!-- /x-panel -->

		<?php 
		if ($page!="Setup")
			echo $current->advancedobject();
		include($GLOBALS["page_inc_path"] . 'headloadjs.php');
		?>

		<script type="text/javascript">
			jQuery(document).ready(function () { 
				<?php
				echo $current->js();
				?>
				
				jQuery(this).isValid();
				// Validation event listeners (Permet d'alimenter la console à chaque validation)
				jQuery('input')
					.on('beforeValidation', function() {
						//console.log(this.name+' - Demande de validation : '+this.value);
					})
					.on('validation', function(evt, valid) {
						//console.log(this.name+'" est ' + (valid ? 'valide' : 'invalide'));
					});
					
				// Module de validation (mixte js + html5)
				if (typeof configvalidate == 'undefined')	
					var configvalidate = {};
				jQuery.validate({
					form : '#ajaxform',
					validateOnBlur : false,
					showHelpOnFocus : false,
					modules : "jsconf, security, html5", //, toggleDisabled",
					lang : 'fr', 
					
					onModulesLoaded : function() {
						jQuery.setupValidation( configvalidate );
					}
					
				});
								
				$('#ajaxform').submit(function(e) {
					//alert("click sur : "+e.originalEvent.explicitOriginalTarget.id);
					// reset error array
					action = jQuery("#action").val();
					e.preventDefault();
					
					if (action!="BT_Annuler") {
						if (( !$(this).isValid() ) && (action=="BT_Envoyer")) {
							//displayErrors( errors );
							e.preventDefault();
							alert("false");
							return false;
						} 	
						else {
							var postData = jQuery("#ajaxform").serializeArray();
							var formURL = "?app=Ws&page=submit.JSON&action="+action;
							$.ajax({
								url : formURL,
								type: "POST",
								data : postData,
								dataType: "json"
							})
							.done(function(json, textStatus, jqXHR) {
								reload = false;
									console.log('errorcode : ' + json.errorcode);
									if (json.errorcode != 0) {
										
										msgType = 'error';
										msgTitle = '<?php echo ucfirst(fn_GetTranslation("error")); ?>';
									}
									else {
										msgType = 'info';
										msgTitle = '<?php echo ucfirst(fn_GetTranslation("information")); ?>';
									}
									if (json.message != "") {
										new PNotify({
											title: msgTitle,
											text: json.message,
											type: msgType,
											nonblock: false		
										});
									}	
									if (json.reload==true) {
									    reload = true;
										numero = json.numero;
									}	
									if (action=="BT_GetCnt") {
										jQuery("#valsetcnt").val(json.brutevalue);
										$cnt=json.brutevalue;
										jQuery("#valconv").val(eval(jQuery("#formule").val()));
									}
									if (action=="BT_Envoyer" && reload==true)
										setTimeout(window.location.href = self.location.href + '&numero=' + numero,4000);
									if (action=="BT_Supprimer")
									<?php 
										if (isset($GLOBALS["config"]->users)) {
											if($_SESSION['Privilege']>=90)
												echo "setTimeout(window.location.href = '?app=Mn&page=List&class=".$class."&addnew=true',2000);"; 
											else
												echo "setTimeout(window.location.href = '?app=Mn&page=List&class=".$class."',2000);"; 
										}
									?>;
							})
							.error(function(jqXHR, textStatus, errorThrown) {
								console.log('error');
								new PNotify({
										title: '<?php echo fn_GetTranslation("error"); ?>',
										text: textStatus,
										type: 'error',
										nonblock: false		
									});
							});
							e.preventDefault();
							return false;
						}
					}


					if (action=="BT_Annuler")
						window.location.href = document.referrer;

					return false;
				
				});	
				
				//Gestion du loader lors d'un appel ajax
				jQuery(document).ajaxStart(function() {jQuery('.loader').show();});
				jQuery(document).ajaxStop(function() {jQuery('.loader').hide();})
				jQuery(document).ajaxComplete(function() {jQuery('.loader').hide();}); 

				jQuery(".select2").select2({ theme: "classic"});
				jQuery("span.select2").removeAttr( "style" );
				
		
				<?php	
				if (isset($_REQUEST["numero"]) && ($_SESSION['Privilege']>=90)) {
				?>
					jQuery('#BT_Supprimer').removeClass("hidden disabled");
				<?php
				}
				?>					
			});
		</script>
