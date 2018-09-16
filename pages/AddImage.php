<?php 
		$return = fn_HtmlStartPanel(ucfirst(fn_GetTranslation('mypictures')), "", "", "");
		$return .= '		<div class="x_content">';
		$return .= '			<form name="AddImage" id="AddImage" class="form-horizontal form-label-left">';
		$return .= '			<div name="AddImageDropzone" id="AddImageDropzone" class="form-horizontal form-label-left"></div>';
		echo $return;
?>
		<div class="ln_solid"></div>
			<!-- Zone de boutons -->
			<div class="form-group">
				<div id="input-btns" class="col-md-12 col-sm-12 col-xs-12">
					<button class="btn btn-dark col-sm-2 col-xs-12 pull-right" id="BT_Annuler" type="submit" name="BT_Annuler" onclick="jQuery('#action').val(this.id);"><?php echo fn_GetTranslation('cancel'); ?></button>
				</div>
			</div>
			<!-- /Zone de boutons -->
		</form>
		
		<!-- template -->		
		<div id="preview-template" style="display: none;">         
		  <div class="dz-preview dz-file-preview">      
			<div class="dz-image">
			  <img data-dz-thumbnail />
			</div>      
			<div class="dz-details">        
			  <div class="dz-size">
				<span data-dz-size>
				</span>
			  </div>        
			  <div class="dz-filename">
				<span data-dz-name>
				</span>
			  </div>      
			</div>      
			<div class="dz-progress">
			  <span class="dz-upload" data-dz-uploadprogress>
			  </span>
			</div>      
			<div class="dz-error-message">
			  <span data-dz-errormessage>
			  </span>
			</div>      
			<div class="dz-success-mark">        
			  <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">          
				<!-- Generator: Sketch 3.2.1 (9971) - http://www.bohemiancoding.com/sketch -->          
				<title>Check</title>          
				<desc>Created with Sketch.</desc>          
				<defs></defs>          
				<g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">              
				  <path d="M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" stroke-opacity="0.198794158" stroke="#747474" fill-opacity="0.816519475" fill="#FFFFFF" sketch:type="MSShapeGroup"></path>          
				</g>        
			  </svg>             
			</div>      
			<div class="dz-error-mark">        
			  <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">            
				<!-- Generator: Sketch 3.2.1 (9971) - http://www.bohemiancoding.com/sketch -->            
				<title>error</title>            
				<desc>Created with Sketch.</desc>            
				<defs></defs>            
				<g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">                
				  <g id="Check-+-Oval-2" sketch:type="MSLayerGroup" stroke="#747474" stroke-opacity="0.198794158" fill="#FFFFFF" fill-opacity="0.816519475">                    
					<path d="M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" sketch:type="MSShapeGroup"></path>                
				  </g>            
				</g>        
			  </svg>      
			</div>    
		  </div>  
		</div>
		<!-- /template -->		

<?php
		include($GLOBALS["page_inc_path"] . 'headloadjs.php');
?>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			Dropzone.autoDiscover = false;
			$("div#AddImageDropzone").addClass("dropzone");
			//fn_PictureResize(128,128,'','','config/images/',value.name);
			var acceptedFileTypes = "image/*";
			var myDropzone = new Dropzone(
				"div#AddImageDropzone",  
				{   previewTemplate: document.querySelector('#preview-template').innerHTML,
					url:'?app=Ws&page=fileupload.JSON',
					clickable:true,
					method:'post',
					maxFiles:100,
					parallelUploads:5,
					thumbnailHeight: 120,
					thumbnailWidth: 120,
					maxFilesize: 5,
					filesizeBase: 1024,
					acceptedFiles: acceptedFileTypes,
					addRemoveLinks:true,
					dictRemoveFile:'Supprimer',
					dictCancelUpload:'Annuler',
					dictCancelUploadConfirmation:'Confirm cancel ?',
					dictDefaultMessage:'Glissez vos fichiers ici',
					dictFallbackMessage:'Your browser does not support drag n drop file uploads',
					dictFallbackText:'Please use the fallback form below to upload your files like in the olden days',
					dictInvalidFileType:'File does not match the allowed file types',
					dictFileTooBig:'File is too big',
					paramName:'file',
					forceFallback:false,
					createImageThumbnails:true,
					maxThumbnailFilesize:1,
					autoProcessQueue:true,
					thumbnail: function(file, dataUrl) {
						 if (file.previewElement) {
							file.previewElement.classList.remove("dz-file-preview");
							var images = file.previewElement.querySelectorAll("[data-dz-thumbnail]");
							for (var i = 0; i < images.length; i++) {
								var thumbnailElement = images[i];
								thumbnailElement.alt = file.name;
								thumbnailElement.src = dataUrl;
							}
							setTimeout(function() { file.previewElement.classList.add("dz-image-preview"); }, 1);
						  }
						},
					init: function() {
						thisDropzone = this;
						$.get(	'?app=Ws&page=fileupload.JSON', 
								function(data) {
									$.each(data, function(key,value){
										var mockFile = { name: value.name, size: value.size };
										thisDropzone.options.addedfile.call(thisDropzone, mockFile);
										thisDropzone.options.thumbnail.call(thisDropzone, mockFile, "config/images/"+value.name);
										$('.dz-preview').addClass("dz-complete");
									});
								}
						);
						
					}
				}
			);
			
			/*
			myDropzone.uploadFiles = function(files) {
				var self = this;
				for (var i = 0; i < files.length; i++) {
					var file = files[i];
					$.ajax({
						url:         "?app=Ws&page=fileupload",
						contentType: "application/json; charset=utf-8",
						data:        { "filename": file.name},
						//beforeSend : function () {DisplayMessage("Traitement en cours...", " ", false);},
						success : 	function(json) {
										$.each(json, function(j, item) { 
											if (item.status == true)
												new PNotify({
													title: '<?php echo fn_GetTranslation("information"); ?>',
													text: "Fichier "+item.filename+" sauvegardé",
													type: 'info',
													nonblock: false		
												});
										});  
									},
						error : 	function() {
										new PNotify({
											title: '<?php echo fn_GetTranslation("error"); ?>',
											text: "Une erreur est survenue",
											type: 'error',
											nonblock: false		
										});
									}
					});
				}
			}
			*/
			/*myDropZone.on("complete", function(file) { alert (file.name);});*/
			myDropzone.on("removedfile", function(file) {
				go = confirm("Effacer ?");
				if (go) {
					$.ajax({
						url:         "?app=Ws&page=filedelete.JSON",
						contentType: "application/json; charset=utf-8",
						data:        { "filename": file.name},
						//beforeSend : function () {DisplayMessage("Traitement en cours...", " ", false);},
						success : 	function(json) {
										$.each(json, function(j, item) { 
													if (item.status == true)
														new PNotify({
															title: '<?php echo fn_GetTranslation("information"); ?>',
															text: "Fichier "+item.filename+" supprimé",
															type: 'info',
															nonblock: false		
														});
													else {
														new PNotify({
															title: '<?php echo fn_GetTranslation("error"); ?>',
															text: "Erreur en suppression",
															type: 'error',
															nonblock: false		
														});
													}
										});  
									},
						error : 	function() {
														new PNotify({
															title: '<?php echo fn_GetTranslation("error"); ?>',
															text: "Une erreur est survenue",
															type: 'error',
															nonblock: false		
														});
									}
		 
					});
			
				}
		/*                
			$.confirm({
					'title'		: 'Confirmation de suppression',
					'message'	: 'Etes vous sur de vouloir supprimer le fichier '+file.name +' ?',
					'buttons'	: {
						'Yes'	: {
							'class'	: 'blue',
							'action': function(){
								alert('ok');
							}
						},
						'No'	: {
							'class'	: 'gray',
							'action': function(){ UpdateMessage('Annulé');}	// Nothing to do in this case. You can as well omit the action property.
						}
					}
				});
		*/

			});

		});
	</script>
