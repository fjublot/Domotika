<?php

$href = "?app=Mn&page=Main";
if ( isset($GLOBALS["config"]->users) && isset($_POST['login']) )
{
	//Recherche de l'utilisateur dans le fichier de conf
	$xpath = "//users/user[label='".$_POST['login']."']";
	$ListUser = $GLOBALS["config"]->xpath($xpath);
	// Si  l'utilisateur a été trouvé
	if ( count($ListUser) == 1 )
	{
		foreach($ListUser as $user)
		{
			//Si le mot de passe correspond
			if ( !isset($_POST['crypt_pass']) )
				$_POST['crypt_pass'] = md5($_POST['pass']);
			else
				$_POST['crypt_pass'] = strtolower($_POST['crypt_pass']);
			if ( $user->pass == $_POST['crypt_pass'] )
			{
				// Si le compte n'est pas actif
				if ( $user->actif != 1 )
				{
					fn_Trace($_POST["login"].' compte non actif', "acces");
					if ( ! isset($_REQUEST["OnLine"]) )
					{
						header ("Refresh: 2;URL=index.php");
						include('Head.php');
						include('Topbar.php');
						echo '<script type="text/javascript">UpdateMsg("'.fn_GetTranslation('compte_non_actif').'", "alert");</script>';
					}
					else
					{
						echo '<?xml version="1.0" encoding="UTF-8" ?><root><error>'.fn_GetTranslation('compte_non_actif').'</error></root>';
						die;
					}
				}
				else
				{
					// Si le compte est actif
					$_SESSION['privilege'] = (int)$user->privilege;
					if ( ! isset($_REQUEST["OnLine"]) )
					{
						if ( isset($_POST['keepconnect']) )
							setcookie("login", $_POST['login'], time()+$GLOBALS["config"]->general->cookie);
						header ("Refresh: 2;URL=index.php");
						include('Head.php');
						include('Topbar.php');
						if ( isset($_POST['basdebit']))
						{
							echo "<script>GetXML('Debit.php?debit=0', AjaxUpdateMessage, '', '')</script>";
						}
						else
						{
							echo "<script>GetXML('Debit.php?debit=1', AjaxUpdateMessage, '', '')</script>";
						}
						echo '<script type="text/javascript">UpdateMsg("'.fn_GetTranslation('good_connexion').'","information");</script>';
					}
					$_SESSION["LoginConn"] = (string)$_POST['login'];
					$_SESSION["AuthId"] = (string)$user->attributes()->numero;
					$_SESSION["timezone"] = (string)$user->timezone;
					$_SESSION["ApiKey"] = md5((string)$user->label) . (string)$user->pass;
					date_default_timezone_set($_SESSION["timezone"]);
					fn_Trace($_SESSION["LoginConn"].' connecte', "acces");
					if ( $user->pushto != "" and $user->notifier=='on')
					{
						$_SESSION["ClientIP"] = getenv("HTTP_X_FORWARDED_FOR") ? getenv("HTTP_X_FORWARDED_FOR") : getenv("REMOTE_ADDR");
						fn_PushTo(fn_GetTranslation('push_good_connexion', $_SESSION["LoginConn"], $_SESSION["ClientIP"]), $user->pushto);
					}
				}
			}
			else
			{
				fn_Trace($_POST["login"].' mot de passe incorrect', "acces");
				if ( ! isset($_REQUEST["OnLine"]) )
				{
					// Si le mot de passe est faux
					header ("Refresh: 2;URL=index.php");
					include('Head.php');
					include('Topbar.php');
					echo '<script type="text/javascript">UpdateMsg("'.fn_GetTranslation('error_connexion').'","alert");</script>';
				}
				else
				{
					echo '<?xml version="1.0" encoding="UTF-8" ?><root><error>'.fn_GetTranslation('error_connexion').'</error></root>';
					die;
				}
			}
		}
	}
	else
	{
		if ( ! isset($_REQUEST["OnLine"]) )
		{
			//Si l'utilisateur n'est pas trouvé
			header ("Refresh: 2;URL=index.php");
			include('Head.php');
			include('Topbar.php');
			echo '<script type="text/javascript">UpdateMsg("'.fn_GetTranslation('error_connexion').'","alert");</script>';
		}
		else
		{
			echo '<?xml version="1.0" encoding="UTF-8" ?><root><error>'.fn_GetTranslation('error_connexion').'</error></root>';
			die;
		}
	}
}
// Si aucun utilisateur n'existe (première utilisation connexion en tant qu'admin)
elseif ( ! isset($GLOBALS["config"]->users) )
{
	header ("Refresh: 2;URL=install.php");
	require('Head.php');
	require('Topbar.php');
	echo '<script type="text/javascript">UpdateMsg("'.fn_GetTranslation('restrict_acces').'","alert");</script>;';
}
elseif ( ! isset($_SESSION["LoginConn"]) )
{
	if ( ! isset($_REQUEST["OnLine"]) )
	{
		header ("Refresh: 2;URL=install.php");
		require('Head.php');
		require('Topbar.php');
		echo '<script type="text/javascript">UpdateMsg("'.fn_GetTranslation('restrict_acces').'","alert");</script>;';
	}
	else
	{
		echo '<?xml version="1.0" encoding="UTF-8" ?><root><error>'.fn_GetTranslation('restrict_acces').'</error></root>';
		die;
	}
}
if ( ! isset($_REQUEST["OnLine"]) )
{
	?>
	<div class="center">
	<div id="composebuttons" class="formbuttons">
	<input class="button mainaction" type="submit" id="BT_Envoyer" name="BT_Envoyer"
	value="<?php echo fn_GetTranslation('continue'); ?>" onclick="javascript:document.location.href='<?php echo $href; ?>';"/>
	</div>
	</div>
	<?php
	include($GLOBALS["root_path"] . 'Foot.php');
}