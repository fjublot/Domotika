<?php		
	include($GLOBALS["page_inc_path"] . 'headloadjs.php');
	
	if(isset($_SESSION['LoginConn'])) {    
		$_SESSION=array();
		if(isset($_COOKIE[session_name()])){
				setcookie("login", "", time());
		}
		session_destroy();
	}
 
	if ( isset($GLOBALS["config"]->syno) && $GLOBALS["config"]->syno == 'true' ) {
		$url = 'http://'.$_SERVER['HTTP_HOST'].':5000/webman/logout.cgi';
		$data = json_decode(wget($url));
	}
	
	echo fn_GetTranslation('do_logout');
?>