<?php
		echo fn_HtmlStartPanel(fn_GetTranslation("restore_config"), "", "", "");
?>
			<div class="x_content">
			        <div class="form-group">
			            <label class="control-label control-label col-md-3 col-sm-3 col-xs-12" for="BT_ConfigPushOnCard">
				            <span class="span-label">&nbsp</span>
			            </label>
			            <?php echo fn_Help('relai.configrelai');?>
			            <div id="input-btns" class="col-md-6 col-sm-6 col-xs-12">
			                <button id="BT_SaveBdd" class="btn btn-primary col-xs-12" name="BT_SaveBdd" type=""><?php echo fn_GetTranslation("save");?></button>
			            </div>
			        </div>
			</div>
		<!-- /x-content -->
		</div> <!-- /form -->			
				
		<?php
			include($GLOBALS["page_inc_path"] . 'headloadjs.php');
		?>
		<script>
		$('#BT_SaveBdd').click(function(e) {
							$.ajax({
								url : "?app=Ws&page=savebdd",
								type: "POST",
								data : "",
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
						});
		</script>