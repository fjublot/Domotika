<?php
	if( isset($_POST['BT_Upload']) )  { // si formulaire soumis 
		move_uploaded_file($_FILES['file_source']['tmp_name'], "./".$_FILES['file_source']['name']);
?>
		<script type="text/javascript">
			jQuery(document).ready(function () { 
				console.log('unzip ok');
				new PNotify({
					title: '<?php echo ucfirst(fn_GetTranslation("information")); ?>',
					text: 'Chargement',
					type: 'info',
					nonblock: false		
				});


<?php
		$zip = new ZipArchive;
		
		if ($zip->open("./".$_FILES['file_source']['name']) === TRUE) {
		$zip->extractTo('.');
		$zip->close();
?>
				console.log('unzip ok');
				new PNotify({
					title: '<?php echo ucfirst(fn_GetTranslation("information")); ?>',
					text: '<?php echo fn_GetTranslation('configrestaure');?>',
					type: 'success',
					nonblock: false		
				});
<?php
		} else {
?>
				console.log('error');
				new PNotify({
					title: '<?php echo ucfirst(fn_GetTranslation("error")); ?>',
					text: '<?php echo fn_GetTranslation('uploaderror', $_FILES['file_source']['error']); ?>',
					type: 'error',
					nonblock: false		
				});
<?php
		}		
	}
		if ( isset($_FILES['file_source']['error']) && $_FILES['file_source']['error'] != 0 ) {
?>
				console.log('error');
				new PNotify({
					title: '<?php echo ucfirst(fn_GetTranslation("error")); ?>',
					text: '<?php echo fn_GetTranslation('uploaderror', $_FILES['file_source']['error']); ?>',
					type: 'error',
					nonblock: false		
				});

<?php
		}
?>
		</script>
<?php
		$return  =  fn_HtmlStartPanel(fn_GetTranslation("restore_config"), "", "", "");
		$return .= '		<div class="x_content">';
		$return .= '			<form class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">';
		echo $return;
?>	
		<div>
			<a class='btn btn-primary' href='javascript:;'>
				<?php echo ucfirst(fn_GetTranslation("select_file")); ?>
				<input type="file" accept=".zip" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="file_source" size="40"  onchange='$("#upload-file-info").html($(this).val());'>
			</a>
			&nbsp;
			<span class='label label-info' id="upload-file-info"></span>
		</div>		
	
					<div class="ln_solid"></div>
					<!-- Zone de boutons -->
					<div class="form-group">
						<div id="input-btns" class="col-md-12 col-sm-12 col-xs-12">
							<button class="actionbutton btn btn-primary col-sm-2 col-xs-12 pull-right toggle-disabled" type="submit" id="BT_Upload" name="BT_Upload"><?php echo fn_GetTranslation('restaure'); ?></button>
						</div>
					</div>
					<!-- /Zone de boutons -->
				</form>
			</div>
			<!-- /x-content -->
		</div> <!-- /form -->			
		
		<?php
			include($GLOBALS["page_inc_path"] . 'headloadjs.php');
		?>