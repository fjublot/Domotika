<?php
	$user = new user(1);
    include($GLOBALS["page_inc_path"] . 'headloadjs.php');
	$ds = DIRECTORY_SEPARATOR;
	$small = array(1 => 'language', 2 => 'prerequisite', 3 => 'informations', 4 => 'technical', 5 => 'mysql');
	$nbsteps = sizeof($small);
	if ($GLOBALS["config"]->general->phppath == "")
		$phppath = $GLOBALS["php_path"];
	else
		$phppath = $GLOBALS["config"]->general->phppath;
	if (!isset($GLOBALS["config"]->general->url)) {	
	
	if (!isset($_SERVER['$HTTP_HOST']))
		$url = "http://localhost";
	else
		$url = 'http://'.$_SERVER['$HTTP_HOST'];
	if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT']!=80)
		$url .=":".$_SERVER['SERVER_PORT'];
	}
	else
		$url = $GLOBALS["config"]->general->url;
	$lang = isset($GLOBALS["config"]->general->lang)?$GLOBALS["config"]->general->lang:"";
	$nameappli = isset($GLOBALS["config"]->general->nameappli)?$GLOBALS["config"]->general->nameappli:"Domotika";
	$mysql = isset($GLOBALS["config"]->general->mysql)?$GLOBALS["config"]->general->mysql:"on";
	$mysql_host = isset($GLOBALS["config"]->general->mysql_host)?$GLOBALS["config"]->general->mysql_host:"127.0.0.1";
	$mysql_port = isset($GLOBALS["config"]->general->mysql_port)?$GLOBALS["config"]->general->mysql_port:"3307";
	$mysql_user = isset($GLOBALS["config"]->general->mysql_user)?$GLOBALS["config"]->general->mysql_user:"root";
	$mysql_password = isset($GLOBALS["config"]->general->mysql_password)?$GLOBALS["config"]->general->mysql_password:"";
	$mysql_db = isset($GLOBALS["config"]->general->mysql_db)?$GLOBALS["config"]->general->mysql_db:"Domotika";
	$mysql_xml = isset($GLOBALS["config"]->general->mysql_xml)?$GLOBALS["config"]->general->mysql_xml:"off";
	$debug = isset($GLOBALS["config"]->general->debug)?$GLOBALS["config"]->general->debug:"off";
	$scan_version_dev = isset($GLOBALS["config"]->general->scan_version_dev)?$GLOBALS["config"]->general->scan_version_dev:"on";
	$informail = isset($GLOBALS["config"]->general->informail)?$GLOBALS["config"]->general->informail:"on";
	$timezone = isset($GLOBALS["config"]->general->timezone)?$GLOBALS["config"]->general->timezone:"Europe/Paris";
	$cookie = isset($GLOBALS["config"]->general->cookie)?$GLOBALS["config"]->general->cookie:"2592000";
	$frequence = isset($GLOBALS["config"]->general->frequence)?$GLOBALS["config"]->general->frequence:"2000";
	
	'off'

?>
	 
	<form action="#" method="POST" id="frmsetup">
		<div id="wizard" class="swMain">
			<ul>
				<?php
				for ($i = 1; $i <= $nbsteps; $i++) {
					$html  = '<li>';
					$html .= '<a href="#step-'.$i.'">';
					$html .= '<span class="stepNumber">'.$i.'</span>';
					$html .= '<span class="stepDesc">'.ucfirst(fn_GetTranslation('step')).' '.$i.'<br />';
					$html .= '<small>'. ucfirst(fn_GetTranslation($small[$i])) . '</small>';
					$html .= '</span>';
					$html .= '</a>';
					$html .= '</li>';
					echo $html;
				}
				?>
			</ul>
			<div class="stepContainer">
				<div id="step1to5" class="x_panel">
					<div class="x_title">
						<h2><i class="fa fa-cog"></i>&nbsp;<?php echo ucfirst(fn_GetTranslation("setup"));?>&nbsp;<small><?php echo ucfirst(fn_GetTranslation("create_config"));?></small></h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<div id="step-1">	
							<div class="form-horizontal form-label-left" style="margin-top: 10px;">
								<?php
									$html  =  fn_HtmlSelectField('lang', 'language', 'install.lang','',false,true);
									$js  = 'AjaxLoadSelectJson("lang", "mysqlenabled=off&class=lang", false, "' . $lang . '");'.PHP_EOL;
									echo $html;
								?>
							</div>          			
						</div>
						<div id="step-2">
							<div class="form-horizontal form-label-left" style="margin-top: 10px;">
							<?php
								$html  = fn_HtmlHiddenField('actif', 'on');
								$html .= fn_HtmlLabel('configdir', 'config', 'config');
								$html .= fn_HtmlLabel('tracedir', 'trace', 'trace');
								$html .= fn_HtmlLabel('capturesdir', 'captures', 'captures');
								//$html .= fn_HtmlLabel('configfile', 'configfile', 'configfile');
								$html .= fn_HtmlLabel('minversionphp', 'versionphp', 'versionphp');  
								$html .= fn_HtmlLabel('session', 'session', 'session');  
								$html .= fn_HtmlLabel('simplexml', 'simplexml', 'simplexml');  
								$html .= fn_HtmlLabel('xml', 'xml', 'xml');  
								$html .= fn_HtmlLabel('sockets', 'sockets', 'sockets');  
								$html .= fn_HtmlLabel('curl', 'curl', 'curl'); 
								$html .= fn_HtmlLabel('allow_url_fopen', 'allow_url_fopen', 'allow_url_fopen'); 
								$html .= fn_HtmlLabel('account', 'account', 'account'); 
								echo $html;
							?>
							</div>        
						</div>                      
						<div id="step-3">
							<div class="form-horizontal form-label-left" style="margin-top: 10px;">
							<?php
								$html  = fn_HtmlInputField('nameappli', $nameappli, 'text', 'application_name', 'install.nomappli', "", true, false,false,false,'data-validation="length" data-validation-length="4-20"');
								$html .= fn_HtmlSelectField('timezone', 'time_zone', 'install.timezone',"",false,true);
								$html .= fn_HtmlBinarySelectField('scan_version_dev', $scan_version_dev, 'scan_version_dev', 'install.scan_version_dev');
								//$html .= fn_HtmlInputField('nameadmin', $_SERVER['SERVER_ADMIN'], 'text', 'admin_name', 'install.nomadmin', "");
								//$html .= fn_HtmlInputField('mailadmin', '', 'text', 'admin_email', 'install.mailadmin', "");
								$html .= fn_HtmlBinarySelectField('informail', $informail , 'send_mail_to_dev', 'install.informail');
								echo $html;
							   ?>
							</div>               				          
						</div>
						<div id="step-4">
							<div class="form-horizontal form-label-left" style="margin-top: 10px;">
							<?php
								$html  = fn_HtmlInputField('url', $url, 'text', 'url', 'install.url', "", true);
								$html .= fn_HtmlInputField('frequence', $frequence, 'text', 'frequence_refresh', 'install.frequence', "", true,false,false,false,'data-validation="number" data-validation-allowing="range[0;60000]"');
								$html .= fn_HtmlInputField('cookie', $cookie, 'text', 'cookies_time_life', 'install.cookie', "", true, false,false,false,'data-validation="number" data-validation-allowing="range[0;2592000]"');
								$html .= fn_HtmlBinarySelectField('debug', $debug, 'debug', 'install.debug');
								$html .= fn_HtmlInputField('phppath', $phppath, 'text', 'phppath', 'install.phppath', "");
								echo $html;
							?>
							</div>                 			
						</div>
						<div id="step-5">
							<div class="form-horizontal form-label-left" style="margin-top: 10px;">
							<?php
								$html  = fn_HtmlBinarySelectField('mysql', $mysql, 'enable_mysql', 'install.mysql');
								$html .= fn_HtmlStartFieldset("", "toggle_mysql_details");
								$html .= fn_HtmlInputField('mysql_host', $mysql_host, 'text', 'mysql_host', 'install.hostmysql', "");
								$html .= fn_HtmlInputField('mysql_port', $mysql_port, 'text', 'mysql_port', 'install.portmysql', "");
								$html .= fn_HtmlInputField('mysql_user', $mysql_user, 'text', 'mysql_account', 'install.comptemysql', "");
								$html .= fn_HtmlInputField('mysql_password', $mysql_password, 'text', 'mysql_password', 'install.passwordmysql', "");
								$html .= fn_HtmlInputField('mysql_db', $mysql_db, 'text', 'mysql_base', 'install.basemysql', "");
								$html .= fn_HtmlBinarySelectField('mysql_xml', $mysql_xml, 'enable_mysql_xml', 'install.xmlmysql');
								$html .= fn_HtmlEndFieldset();
								echo $html;
							?>
							</div>                 			
						</div>
					</div><!-- /x-content -->
				</div><!-- /x-panel -->
			</div><!-- /Stepcontainer -->
		</div><!-- /Wizard -->
	<!-- End SmartWizard Content -->  		
	</form> 
			

	<script type="text/javascript">
		$(document).ready(function(){
			jQuery.validate({
				form : '#frmsetup',
				modules : "jsconf, security, html5", //, toggleDisabled",
				keyNavigation: false, // Pour éviter la navigation entre onglets avec les flèches
				validateOnBlur : false,
				showHelpOnFocus : false,
				lang : 'fr', 
				onElementValidate : function(valid, $el, $form, errorMess) {
					console.log('Input ' +$el.attr('name')+ ' is ' + ( valid ? 'VALID':'NOT VALID') );
				}
  		});
			
			//Gestion du loader lors d'un appel ajax
			jQuery(document).ajaxStart(function() {jQuery('.loader').show();});
			jQuery(document).ajaxStop(function() {jQuery('.loader').hide();})
			jQuery(document).ajaxComplete(function() {jQuery('.loader').hide();}); 

			jQuery('#mysql').change(function(){
				if ($(this).prop('checked')) {
				jQuery('#mysql_host').prop('disabled', false);
				jQuery('#mysql_port').prop('disabled', false);
				jQuery('#mysql_user').prop('disabled', false);
				jQuery('#mysql_password').prop('disabled', false);
				jQuery('#mysql_db').prop('disabled', false);
				jQuery('#mysql_xml').bootstrapToggle('enable');
				$("#toggle_mysql_details").slideToggle("slow"); $(this).toggleClass("toggleafficher");
				}
				else {
					jQuery('#mysql_host').prop('disabled', true);
					jQuery('#mysql_port').prop('disabled', true);
					jQuery('#mysql_user').prop('disabled', true);
					jQuery('#mysql_password').prop('disabled', true);
					jQuery('#mysql_db').prop('disabled', true);
					jQuery('#mysql_xml').bootstrapToggle('disable');
					$("#toggle_mysql_details").slideToggle("slow");
				}
				});
				

				// Smart Wizard     	
				$('#wizard').smartWizard({
				// Properties
					selected: 0,  // Selected Step, 0 = first step   
					keyNavigation: true, // Enable/Disable key navigation(left and right keys are used if enabled)
					enableAllSteps: false,  // Enable/Disable all steps on first load
					transitionEffect: 'fade', // Effect on navigation, none/fade/slide/slideleft
					contentURL:null, // specifying content url enables ajax content loading
					contentURLData:null, // override ajax query parameters
					contentCache:true, // cache step contents, if false content is fetched always from ajax url
					cycleSteps: false, // cycle step navigation
					enableFinishButton: true, // makes finish button enabled always
					hideButtonsOnDisabled: false, // when the previous/next/finish buttons are disabled, hide them instead
					errorSteps:[],    // array of step numbers to highlighting as error steps
					labelNext: '<?php echo ucfirst(fn_GetTranslation('next'));?>', // label for Next button
					labelPrevious: '<?php echo ucfirst(fn_GetTranslation('previous'));?>', // label for Previous button
					labelFinish: '<?php echo ucfirst(fn_GetTranslation('finish'));?>',  // label for Finish button        
					noForwardJumping:false,
					ajaxType: 'POST',
				  // Events
					onLeaveStep: leaveAStepCallback, // triggers when leaving a step
					onShowStep: showAStepCallback,  // triggers when showing a step
					onFinish: onFinishCallback,  // triggers when Finish button is clicked  
					buttonOrder: ['finish', 'next', 'prev']  // button order, to hide a button remove it from the list
				});
					<?php echo $js;?>
				
				function leaveAStepCallback(obj){
					var vStepNum= obj.attr('rel');
					console.log ("validateSteps("+vStepNum +")");
					return validateSteps(vStepNum);
				}
				function showAStepCallback(obj){
					var vStepNum= obj.attr('rel');
					console.log ("jQuery.validate('#step-"+vStepNum +"')");
<?php 	if (! file_exists($GLOBALS["config_file"])) { ?>
					if (vStepNum == '6') 
						jQuery('#step1to5').hide();
					else
						jQuery('#step1to5').show();
<?php } ?>
					return jQuery.validate('#step-'+vStepNum);
				}
				
			function onFinishCallback(){
				if (validateAllSteps()) { 
					ajaxCreateConfigFile();

				}
			}

		AjaxLoadSelectJson("timezone", "mysqlenabled=off&class=timezone", false, "<?php echo $timezone;?>");
		AjaxControl("mysqlenabled=off&control=iswritabledir&directory=config","configdir");
		AjaxControl("mysqlenabled=off&control=iswritabledir&directory=trace","tracedir");
		AjaxControl("mysqlenabled=off&control=iswritabledir&directory=captures","capturesdir");
		AjaxControl("mysqlenabled=off&control=phpminversion&minversion=5.2.0","minversionphp");
		AjaxControl("mysqlenabled=off&control=extensionisloaded&extension=session","session");
		AjaxControl("mysqlenabled=off&control=extensionisloaded&extension=simplexml","simplexml");
		AjaxControl("mysqlenabled=off&control=extensionisloaded&extension=xml","xml");
		AjaxControl("mysqlenabled=off&control=extensionisloaded&extension=sockets","sockets");
		AjaxControl("mysqlenabled=off&control=extensionisloaded&extension=curl","curl");
		AjaxControl("mysqlenabled=off&control=optionenable&option=allow_url_fopen","allow_url_fopen");
		AjaxControl("mysqlenabled=off&control=accountserver","account");

		   
		// Validation de tous les step
		function validateAllSteps(){
			var isStepValid = true;
			for (i = 1; i <= <?php echo $nbsteps;?>; i++) {   
				if(validateStep(''+i) == false){
					isStepValid = false;
					$('#wizard').smartWizard('setError',{stepnum:i,iserror:true});         
				}
				else {
					$('#wizard').smartWizard('setError',{stepnum:i,iserror:false});
				}
			}

			if(!isStepValid){
				//$('#wizard').smartWizard('showMessage','Please correct the errors in the steps and continue');
				new PNotify({
					title: '<?php echo ucfirst(fn_GetTranslation('step'));?>',
					text: '<?php echo ucfirst(fn_GetTranslation('correct_to_continue'));?>',
					type: 'error',
					nonblock: false		
				});				
			}
				  
		   return isStepValid;
		} 	
			
			
		function validateSteps(step){
			var isStepValid = true;
			if (validateStep(step) == false ){
				isStepValid = false; 
				new PNotify({
					title: '<?php echo ucfirst(fn_GetTranslation('step'));?> ' + step,
					text: '<?php echo ucfirst(fn_GetTranslation('correct_to_continue'));?>',
					type: 'error',
					nonblock: false		
				});				
				$('#wizard').smartWizard('setError',{stepnum:step,iserror:true});         
			}
			else {
				$('#wizard').smartWizard('setError',{stepnum:step,iserror:false});
			}
			return isStepValid;
		}
		
		function validateStep(step) {
			var isValid = false; 
			switch (step) {			
				case '1':
					isValid = true;
					firstStepError = '1';
					break;
				case '2':
					isValid = true;
					if ($("#step-2 .icon-valid.error").length >0)
						isValid = false;
					break;
				case '3':
					isValid = $('#step-3').isValid();
					firstStepError = '3';
					break;
				case '4':
					isValid = $('#step-4').isValid();
					firstStepError = '4';
					break;
				case '5':
					isValid = $('#step-5').isValid();
					firstStepError = '5';
					if (isValid) {
						console.log ($("#mysql").prop('checked'));
						if ($("#mysql").prop('checked')) { 
								isValid = ajaxCreateDb();
								console.log ('Création base de données - '+isValid);
						}
					}
					break;
				case '6':
					isValid = $('#step-6').isValid();
					firstStepError = '6';
					break;
			}
			console.log ('Validate step ' + step + ' = '+isValid);
			return isValid;
		}
		

		/* Email Validation
		function isValidEmailAddress(emailAddress) {
			var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
			return pattern.test(emailAddress);
		} 
		*/
		
		function ajaxCreateDb() {
			var vformURL = "?app=Ws&mysqlenabled=off&page=installdatabase.JSON";
			var vPostData = jQuery("#frmsetup").serialize();
			var vCreateDbStatus = false;
			$.ajax({
				url : vformURL,
				type: "GET",
				dataType: "json",
				data: vPostData,
				async: false
			})
			.done(function(json, textStatus, jqXHR) {
				console.log(json);
				message="";
            	for (var i in json.actions) {
					message=message + json.actions[i].message + "\n";
				}
				new PNotify({
					title: json.valid ? '<?php echo ucfirst(fn_GetTranslation("information")); ?>' : '<?php echo ucfirst(fn_GetTranslation("error")); ?>',
					text: message,
					type: json.valid ? 'info':'error',
					nonblock: false		
				});
				vCreateDbStatus = json.valid;
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				console.log('error');
				new PNotify({
						title: '<?php echo ucfirst(fn_GetTranslation("information")); ?>',
						text: textStatus,
						type: 'error',
						nonblock: false		
					});
				vCreateDbStatus = false;
				
			});
			//e.preventDefault();
			return vCreateDbStatus;
		};	

		
		function ajaxCreateConfigFile() {
			var vformURL = "?app=Ws&page=createconfig.JSON&mysqlenabled=off";
			var vPostData = jQuery("#frmsetup").serialize();
			var vCreateConfig = false;
			$.ajax({
				url : vformURL,
				type: "GET",
				dataType: "json",
				data: vPostData,
				async: false
			})
			.done(function(json, textStatus, jqXHR) {
				message="";
				if (json.valid) {
					new PNotify({
						title: '<?php echo ucfirst(fn_GetTranslation("information")); ?>',
						text: json.message,
						type: json.valid ? 'info':'error',
						nonblock: false		
					});
					
					var dialog = bootbox.dialog({
						title: '<?php echo ucfirst(fn_GetTranslation('install_done'));?>',
						message: '<p><?php echo ucfirst(fn_GetTranslation("congratulations")); ?></p><p></p><p id="msginstalldone"></p>',
						closeButton: false,
						buttons: {
							ok: {
								label: '<?php echo ucfirst(fn_GetTranslation("connect"));?>',
								className: 'btn-primary btn col-sm-2 col-xs-12',
								callback: function(){
									document.location.href="?app=Mn";
								}
							},
							/*cancel: {
								label: "cancel",
								className: 'btn-danger',
							},*/							
						}
					});
					dialog.init(function(json) {
						console.log("Installation terminée.")
						messagecron = "<?php echo $phppath;?>";
						dialog.find('.bootbox-body #msginstalldone').html(json.phppath);
					});
				}
				else {
					new PNotify({
						title: json.valid ? '<?php echo ucfirst(fn_GetTranslation("information")); ?>' : '<?php echo ucfirst(fn_GetTranslation("error")); ?>',
						text: message,
						type: json.valid ? 'info':'error',
						nonblock: false		
					});
					vCreateConfig = json.valid;
				}
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				console.log('error');
				new PNotify({
						title: '<?php echo ucfirst(fn_GetTranslation("information")); ?>',
						text: textStatus,
						type: 'error',
						nonblock: false		
					});
				vCreateDbStatus = false;
				
			});
			//e.preventDefault();
			return vCreateConfig;
		};	
		
	});
	</script>
	</div>
