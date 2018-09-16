<?php
	class login {
		private $timeout         = null;
		private $target_element  = null;
		private $wait_text       = null;
		private $form_element    = null;
		private $wait_element    = null;
		private $notify_element  = null;
		
		function __construct() {
			/* Advance Configuration - no need to edit this section */
			define('LOGIN_TIMEOUT',        '10000000');
			define('LOGIN_TARGET_ELEMENT', 'login_target');
			define('LOGIN_WAIT_TEXT',      'Please wait...');
			define('LOGIN_FORM_ELEMENT',   'login_form');
			define('LOGIN_WAIT_ELEMENT',   'login_wait');
			define('LOGIN_NOTIFY_ELEMENT', 'login_notify');
			define('SUCCESS_LOGIN_GOTO'   ,'?app=Mn&page=index');
		}
		
		function get_config() {
			$this->set_ajax_config();
		}
		
		function set_ajax_config() {
			$this->timeout         = LOGIN_TIMEOUT;
			$this->target_element  = LOGIN_TARGET_ELEMENT;
			$this->wait_text       = LOGIN_WAIT_TEXT;
			$this->wait_element    = LOGIN_WAIT_ELEMENT;
			$this->notify_element  = LOGIN_NOTIFY_ELEMENT;
			$this->form_element    = LOGIN_FORM_ELEMENT;
		}
		
		function initLogin($arg = array()) {
			$this->get_config();
			$this->login_script();
		}
		
		function initJquery() {
			return '<script src="ressources/jquery/jquery-last.min.js"></script>';
		}
		
		function login_script() {
			include ('js/login/loginjs.php');
		}
		
		function set_session() {
			if (isset($GLOBALS["config"]->general->namesession))
				session_name((string)$GLOBALS["config"]->general->namesession);
			else
				session_name("installation_of_ipx800multicard");
			session_start();
			$_SESSION["SuccessLogin"] = true;				

		}
		
		function kill_session() {
			// Détruit toutes les variables de session
			$current_session_id = session_id();
			$current_session_name = session_name();
			
			foreach($_SESSION as $k => $v) 
				$current_session[$k]=$v;
			$_SESSION = array();

			// Si vous voulez détruire complètement la session, effacez également
			// le cookie de session.
			// Note : cela détruira la session et pas seulement les données de session !
			if (ini_get("session.use_cookies")) {
				$params = session_get_cookie_params();
				setcookie(session_name(), '', time() - 42000,
					$params["path"], $params["domain"],
					$params["secure"], $params["httponly"]
				);
			}
			
			// Cas du synology
			if ( isset($GLOBALS["config"]->syno) && $GLOBALS["config"]->syno == 'true' ) {
				$url = 'http://'.$_SERVER['HTTP_HOST'].':5000/webman/logout.cgi';
				$data = json_decode(wget($url));
			}

			// Finalement, on détruit la session.
			$retour = session_destroy();	
			
			if ($retour===true) {
				$result['sessionid']    = $current_session_id;
				$result['sessionname']  = $current_session_name;
				$result['msg']   		= ucfirst(fn_GetTranslation('disconnected'));
				foreach($current_session as $k => $v) 
					$result[$k] = $v;
					
			}
			else {
				$result['sessionid']    = $current_session_id;
				$result['sessionname']  = $current_session_name;
				$result['msg']   		= ucfirst(fn_GetTranslation('error_disconnected'));
			}			
			return $result;
		}		
		
		function css($arg = array()) {
			$this->login_css();
		}
		
		function login_css() {
		echo '<link href="css/login/login.css" rel="stylesheet">';
		echo '<link href="fonts/css/font-awesome.min.css" rel="stylesheet">';
		}
	  
	  function html() {
	  ?>
		<div class="container">    
			<div class="modal-ish">     
				<div class="modal-header">      <h2>Sign In</h2>      <h6>
					<?php echo $GLOBALS["config"]->general->nameappli . '(' . $_SERVER['SERVER_ADDR'] . ')'; ?></h6>  
				</div>     
				<form id="loginform" method="post" action="">      
					<div class="modal-body">                                                                                    
						<input name="ajax" id="ajax" value="Ko" type="hidden" />      
						<p>                             
						  <label>
							<?php echo fn_GetTranslation('compte'); ?>
						  </label>                             
						  <input id="username" name="username" type="text"  name="username" autocomplete="off" placeholder="<?php echo ucfirst(fn_GetTranslation('compte')); ?>" value="" />                       
						</p>                                        
						<p>                              
						  <label>
							<?php echo fn_GetTranslation('password'); ?>
						  </label>                              
						  <input id="password" name="password" type="password" name="password" placeholder="<?php echo ucfirst(fn_GetTranslation('password')); ?>" value="" />                       
						</p>                         
						<p>		              
						  <input type="checkbox" name="keepconnect" value=1/>        
						  <label><small>
							  <?php echo fn_GetTranslation('stay_connected'); ?></small>
						  </label>      
						</p>                 	                                                         
					</div>                 
					<div class="modal-footer">  
						<div class="login_wait">    
							<i class="fa fa-spinner fa-2x fa-spin"></i>  
						</div>  
						<div class="login_notify"></div>    
						<div>       
							<input name="submit" type="submit" style="display:none" />       
							<a class="btn btn-primary btn-sign" href="#" onclick="javascript:$('#loginform').submit();return false;">Sign In</a>    
						</div>    
					</div>    
				</form>                                         
			  </div>
			  <div class="clear">
			  </div>
			  <p style="margin-top:30px; text-align:center;">  
				<a href="?app=Mn&page=ForgotPassword">
				  <?php echo fn_GetTranslation('forgot_password'); ?>
				</a> 
				<br>
				<?php echo fn_GetIP(); ?>
				<!--| 
				  <a href="/">Home Page</a>-->
			  </p>
			</div>

	  <?php
	  }
	}

?>