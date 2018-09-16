<?php
	header('Content-Type: text/xml; charset: UTF-8');
	header("Cache-Control: no-cache");
	fn_Trace("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],"xml");
	echo '<VerifXml>';
	echo '<debug>'.$GLOBALS["config"]->general->debug.'</debug>';
	switch ($_REQUEST['type'])
	{
		Case "ip":
			if ($ip != '') { 
				$detail = $ip;
				if ( preg_match("/^[1-9]/", $ip) ) {
					if ( fn_CheckIp($ip) ) {
						$status='OK';
						$statusmessage=htmlspecialchars(fn_GetTranslation('good_ip'));
					}
					elseif ( fn_CheckIpCidr($ip) ) {
						$status='OK';
						$statusmessage=htmlspecialchars(fn_GetTranslation('good_ip_range'));
					}
					else {
						$status='KO';
						$statusmessage=htmlspecialchars(fn_GetTranslation('bad_ip'));
					}
				}
				else
				{
					$ip = gethostbyname($ip);
					if( isset($ip) )
					{
						if ( fn_CheckIp($ip) )
						// Vérifie si la chaine ressemble à une adresse IP
						{
							$status='OK';
							$statusmessage=htmlspecialchars(fn_GetTranslation('good_ip'));
						}
						else
						{
							$status='KO';
							$statusmessage=htmlspecialchars(fn_GetTranslation('bad_ip'));
						}
					}
					else
					{
						$status='KO';
						$statusmessage=htmlspecialchars(fn_GetTranslation('not_defined_ip'));
					}
				}
			}
			else {	
						$status='KO';
						$statusmessage=htmlspecialchars(fn_GetTranslation('not_defined_ip'));
			}
			break;
		Case "reseau":
			$detail = $_REQUEST['reseau'];
			if (strpos($detail, '/')===false)
			{$ip=$detail;
			 $reseau=false;
			}
			else
			list($ip, $reseau) = explode("/", $_REQUEST['reseau']);
			if ( isset($reseau) && ( ! preg_match("/^[0-9]+$/", $reseau) || $reseau < 0 || $reseau > 32 ) )
			{
					$status='KO';
					$detail = $reseau;
					$statusmessage=htmlspecialchars(fn_GetTranslation('bad_netmask'));
			}
			elseif ( isset($reseau) && !ValidateIP($ip) )
			{
				$status='KO';
				$statusmessage=htmlspecialchars(fn_GetTranslation('bad_ip'));
			}
			else
			{
				if ( preg_match("/^[1-9]/", $ip) )
				{
					if ( ValidateIP($ip) )
					{
						$status='OK';
						$statusmessage=htmlspecialchars(fn_GetTranslation('good_ip'));
					}
					else
					{
						$status='KO';
						$statusmessage=htmlspecialchars(fn_GetTranslation('bad_ip'));
					}
				}
				else
				{
					$ip = gethostbyname($ip);
					if( isset($ip) )
					{
						if ( ValidateIP($ip) )
						{
							$status='OK';
							$statusmessage=htmlspecialchars(fn_GetTranslation('good_ip'));
						}
						else
						{
							$status='KO';
							$statusmessage=htmlspecialchars(fn_GetTranslation('bad_ip'));
						}
					}
					else
					{
						$status='KO';
						$statusmessage=htmlspecialchars(fn_GetTranslation('not_defined_ip'));
					}
				}
			}
			break;
		case "port":
			$detail = $_REQUEST['port'];
			if ( (preg_match("/^[0-9]+$/", $_REQUEST['port']) && $_REQUEST['port'] <= 65535) or $detail==""  )
			// Vérifie si la chaine ressemble à un port
			{
				$status='OK';
				$statusmessage=htmlspecialchars(fn_GetTranslation('good_port'));
			}
			else
			{
				$status='KO';
				$statusmessage=htmlspecialchars(fn_GetTranslation('bad_port'));
			}
			break;
		case "login":
			$detail = $_REQUEST['login'];
			$statusmessage = htmlspecialchars(fn_GetTranslation('good_login'));
			$status='OK';
			if ( ! preg_match("/^[A-Za-z0-9_]{4,20}$/", $_REQUEST['login']) )
			{
				$statusmessage = htmlspecialchars(fn_GetTranslation('bad_login', 4, 20));
				$status='KO';
			}
			elseif ( $_REQUEST['login'] == "admin" )
			{
				$statusmessage = htmlspecialchars(fn_GetTranslation('admin_login_prohibited'));
				$status='KO';
			}
			elseif ( isset($GLOBALS["config"]->users) )
			{
				$query='//users/user[label="'.$_REQUEST['login'].'" and @numero!="'.$_REQUEST['numero'].'"]';
				$ListUsers = $GLOBALS["config"]->xpath($query);
				if ( count($ListUsers) != 0)
				{
						$statusmessage = htmlspecialchars((fn_GetTranslation('already_used_login')));
					$status='KO';
				}
			}
			break;
		case "password":
			$detail = $_REQUEST['password'];
			$statusmessage = htmlspecialchars(fn_GetTranslation('good_password'));
			$status='OK';
			if( strlen($_REQUEST['password']) < 4 )
			{
				$statusmessage = htmlspecialchars(fn_GetTranslation('bad_password', 4));
				$status='KO';
			}
			break;
		case "passconfirm":
			$detail = $_REQUEST['passconfirm'];
			$statusmessage = htmlspecialchars(fn_GetTranslation('good_password'));
			$status='OK';
			if( $_REQUEST['passconfirm'] != $_REQUEST['pass'] )
			{
				$statusmessage = htmlspecialchars(fn_GetTranslation('bad_verif_password'));
				$status='KO';
			}
			break;
		case "mail":
			$detail = $_REQUEST['mail'];
			if ( fn_CheckEmail($_REQUEST['mail']) )
			{
				$status='OK';
				$statusmessage=htmlspecialchars(fn_GetTranslation('good_mail'));
			}
			else
			{
				$status='KO';
				$statusmessage=htmlspecialchars(fn_GetTranslation('bad_mail'));
			}
			break;
		case "getctrl":
			$detail = $relai;
			if ( $relai != "" )
			{
				$current_relai = new relai($relai);
				$status = 'OK';
				$model = fn_GetModel("carte", $current_relai->ctrlcarte);
				$ctrlcarte = new $model($current_relai->ctrlcarte);
				$xmlctrlStatus = simplexml_load_file($ctrlcarte->geturl()."status.xml");
				$controle_btn = new btn($current_relai->ctrlbtn);
				$controle_btn->getxmlvalue($xmlctrlStatus);
				$status = 'OK';
				if ( $controle_btn->valeur == 1 )
				{
					$statusmessage = $current_relai->ctrlmessageup;
				}
				else
				{
					$statusmessage = $current_relai->ctrlmessagedn;
				}
			}
			else
			{
				$status = 'KO';
				$statusmessage = htmlspecialchars(fn_GetTranslation('no_control'));
			}
			break;
		case "compensation":
			$detail = $_REQUEST['typean'];
			$status = 'KO';
			$statusmessage = htmlspecialchars(fn_GetTranslation('no_compensation'));
			if ( isset($_REQUEST['typean']) )
			{
				foreach($GLOBALS["config"]->typeans->typean as $info)
				{
					if ( $info->attributes()->numero != $_REQUEST['typean'] )
						continue;
					if ( $info->compensation != "" )
					{
						$status = 'OK';
						$statusmessage = $info->compensation;
					}
				}
			}
			break;
	}
	echo '<type>'.$_REQUEST['type'].'</type>';
	if ( isset($_REQUEST['img']) )
		echo '<img>'.$_REQUEST['img'].'</img>';
	echo '<status>'.$status.'</status>';
	echo '<statusmessage>'.utf8_encode($statusmessage).'</statusmessage>';
	echo '<detail>'.$detail.'</detail>';        
	echo '</VerifXml>';
?>