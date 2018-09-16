<?php
$L_users = array();
if ( file_exists("config/Users.conf") )
{
	require_once("class/user.php");
	$xmlConf = simplexml_load_file("config/Users.conf");
	unlink("config/Users.conf");
	$userConf = $xmlConf->xpath('//UsersConf/users/user');
	if ( count($userConf) != 0 )
	{
		foreach ($userConf as $item)
		{
			echo fn_GetTranslation('recup_info', $item->login)."<br>";
			$item->label = $item->login;
			$login = (string)$item->login;
			$id = $item->attributes()->id + 1;
			$L_users[$login] = $id;
			$item->actif = $item->compteactive;
			$item->label = $item->login;
			$item->timezone = "Europe/Paris";
			$current = new user($id, $item);
			$current->update();
			$current->save();
		}
	}
}
if ( file_exists("config/Auth.conf") )
{
	echo fn_GetTranslation('recup_info', 'right')."<br>";
	$xmlConf = simplexml_load_file("config/Auth.conf");
	unlink("config/Auth.conf");
	$users = $xmlConf->xpath('//users/user');
	if ( count($users) != 0 )
	{
		foreach ($users as $user)
		{
			foreach ($user->commandes->relais as $relais)
			{
				if ( preg_match("/^C([0-9]*)R([0-9]*)$/", $relais->attributes()->name, $regs) )
				{
					fn_SetAuth($user->attributes()->id, "relai", "carte-".$regs[1]."-relai-".$regs[2], (string)$relais[0]);
				}
			}
			foreach ($user->etats->btn as $btn)
			{
				if ( preg_match("/^C([0-9]*)BTN([0-9]*)$/", $btn->attributes()->name, $regs) )
				{
					fn_SetAuth($user->attributes()->id, "btn", "carte-".$regs[1]."-btn-".$regs[2], (string)$btn[0]);
				}
			}
			foreach ($user->analog->an as $an)
			{
				if ( preg_match("/^C([0-9]*)AN([0-9]*)$/", $an->attributes()->name, $regs) )
				{
					fn_SetAuth($user->attributes()->id, "an", "carte-".$regs[1]."-an-".$regs[2], (string)$an[0]);
				}
			}
			foreach ($user->counters->cnt as $cnt)
			{
				if ( preg_match("/^C([0-9]*)CNT([0-9]*)$/", $cnt->attributes()->name, $regs) )
				{
					fn_SetAuth($user->attributes()->id, "an", "carte-".$regs[1]."-cnt-".$regs[2], (string)$cnt[0]);
				}
			}
			foreach ($user->cams->cam as $cam)
			{
				fn_SetAuth($user->attributes()->id, "camera", $cnt->attributes()->id, (string)$cam[0]);
			}
		}
	}
}
if ( file_exists("config/Cams.conf") )
{
	require_once("class/camera.php");
	$xmlConf = simplexml_load_file("config/Cams.conf");
	unlink("config/Cams.conf");
	$users = $xmlConf->xpath('//ConfCams/cams/cam');
	if ( count($users) != 0 )
	{
		foreach ($users as $item)
		{
			echo fn_GetTranslation('recup_info', $item->label)."<br>";
			$item->model = $item->type;
			$item->password = $item->pwd;
			$current = new camera($item->attributes()->id, $item);
			foreach ($item->ctrl as $ctrl)
			{
				if ( $ctrl->carte != "#N/A" )
				{
					array_push($current->cderelai, "carte-".$ctrl->carte."-relai-".$ctrl->relais);
				}
			}
			$current->update();
			$current->save();
		}
	}
}
if ( file_exists("config/ConnAuto.conf") )
{
	require_once("class/connexionauto.php");
	$xmlConf = simplexml_load_file("config/ConnAuto.conf");
	unlink("config/ConnAuto.conf");
	$ConnAuto = $xmlConf->xpath('//ConnAutoConf');
	echo fn_GetTranslation('recup_info', "autoconnect").".<br>";
	if ( count($ConnAuto) != 0 )
	{
		foreach ($ConnAuto as $item)
		{
			$item->label = "Depuis ".$item->ip;
			$item->compte = $L_users[(string)$item->login];
			$current = new connexionauto($item->attributes()->id, $item);
			$current->update();
			$current->save();
		}
	}
}
if ( file_exists("config/PushMeTo.conf") && file_exists("class/pushmeto.php") )
{
	require_once("class/pushmeto.php");
	$xmlConf = simplexml_load_file("config/PushMeTo.conf");
	unlink("config/PushMeTo.conf");
	$ConnAuto = $xmlConf->xpath('//PushMeToConf');
	echo fn_GetTranslation('recup_info', "pushto").".<br>";
	if ( count($ConnAuto) != 0 )
	{
		foreach ($ConnAuto as $item)
		{
			if ( $item->username != "compte" )
			{
				$item->label = $item->username;
				$item->signature = $item->signature;
				$item->actif = 1;
				$current = new pushmeto(1, $item);
				$current->update();
				$current->save();
			}
		}
	}
}
if ( file_exists("config/Www.conf") )
{
	unlink("config/Www.conf");
}
if ( file_exists("config/Relais.conf") )
{
	require_once("class/carte.php");
	$xmlConf = simplexml_load_file("config/Relais.conf");
	unlink("config/Relais.conf");
	$Conf = $xmlConf->xpath('//ConfRelais/cartes/carte');
	echo fn_GetTranslation('recup_info', "carte").".<br>";
	if ( count($Conf) != 0 )
	{
		foreach ($Conf as $item)
		{
			$item->password = $item->pwd;
			if ( $item->pushautonome == "N" || $item->pushautonome == "0" )
				$item->pushautonome = 0;
			else
				$item->pushautonome = 1;
			$item->version = $Modele[0]->attributes()->numero;
			$model = fn_GetModel("carte", $item->attributes()->id);
			$current = new $model($item->attributes()->id, $item);
			$current->update();
			$current->save();
		}
	}
	$Conf = $xmlConf->xpath('//ConfRelais/commandes/relais');
	echo fn_GetTranslation('recup_info', "relais").".<br>";
    if ( count($Conf) != 0 )
	{
		foreach ($Conf as $item)
		{
			if ( preg_match("/^C([0-9]*)R([0-9]*)$/", $item->attributes()->name, $regs) )
			{
				$item->numero = "carte-".$regs[1]."-relai-".$regs[2];
				$item->no = $regs[2] - 1;
			}
			$item->carteid = $item->carte;
			$item->imageon = "images/relais/".$item->imageon;
			$item->imageoff = "images/relais/".$item->imageoff;
			if ( $item->push == "N" )
				$item->push = 0;
			else
				$item->push = 1;
			$item->ctrlcarte = $item->ctrl->ctrlcarte;
			$item->ctrlbtn = $item->ctrl->ctrlnobtn;
			$item->ctrlmessageup = $item->ctrl->ctrlmessageup;
			$item->ctrlmessagedn = $item->ctrl->ctrlmessagedn;
			$current = new relai($item->numero, $item);
			$current->update();
			$current->save();
		}
	}
	$Conf = $xmlConf->xpath('//ConfRelais/etats/btn');
	echo fn_GetTranslation('recup_info', "btn").".<br>";
    if ( count($Conf) != 0 )
	{
		foreach ($Conf as $item)
		{
			if ( preg_match("/^C([0-9]*)BTN([0-9]*)$/", $item->attributes()->name, $regs) )
			{
				$item->numero = "carte-".$regs[1]."-btn-".$regs[2];
				$item->no = $regs[2] - 1;
			}
			$item->carteid = $item->carte;
			$item->imageup = "images/btns/".$item->imageup;
			$item->imagedn = "images/btns/".$item->imagedn;
			unset($item->txtup);
			unset($item->txtdn);
			if ( $item->push == "N" )
				$item->push = 0;
			else
				$item->push = 1;
			$current = new btn($item->numero, $item);
			$current->update();
			$current->save();
		}
	}
	$Conf = $xmlConf->xpath('//ConfRelais/analog/an');
	echo fn_GetTranslation('recup_info', "an").".<br>";
	if ( count($Conf) != 0 )
	{
		foreach ($Conf as $item)
		{
			if ( preg_match("/^C([0-9]*)AN([0-9]*)$/", $item->attributes()->name, $regs) )
			{
				$item->numero = "carte-".$regs[1]."-an-".$regs[2];
			}
			$item->no = $item->noan;
			$item->carteid = $item->carte;
			$current = new an($item->numero, $item);
			$current->update();
			$current->save();
		}
	}
	$Conf = $xmlConf->xpath('//ConfRelais/counters/cnt');
	echo fn_GetTranslation('recup_info', "cnt").".<br>";
	if ( count($Conf) != 0 )
	{
		foreach ($Conf as $item)
		{
			if ( preg_match("/^C([0-9]*)CNT([0-9]*)$/", $item->attributes()->name, $regs) )
			{
				$item->numero = "carte-".$regs[1]."-cnt-".$regs[2];
				$item->no = $regs[2];
			}
			$item->carteid = $item->carte;
			$current = new cnt($item->numero, $item);
			$current->update();
			$current->save();
		}
	}
}
if ( file_exists("config/Www.conf") )
{
	unlink("config/Www.conf");
}
if ( file_exists("config/CamsTypes.conf") )
{
	unlink("config/CamsTypes.conf");
}
if ( file_exists("config/Modele.conf") )
{
	unlink("config/Modele.conf");
}
require_once("LoadConfig.php");
if ( isset($GLOBALS["config"]->pushmetos) )
{
	echo fn_GetTranslation('recup_info', "pushmetos").".<br>";
	require_once("class/pushmeto.php");
	require_once("class/pushto.php");
	foreach($GLOBALS["config"]->pushtos->pushto as $info)
	{
		$pushmeto = new pushmeto($numero, $info);
		$pushto = new fn_PushTo();
		$pushto->type = "pushmeto";
		$pushto->label = $pushto->label;
		$pushto->signature = $pushto->signature;
		$pushto->actif = $pushto->actif;
		$pushto->timezone = $pushto->timezone;
		$pushto->save();
	}
}
if ( file_exists("class/pushmeto.php") )
	unlink("class/pushmeto.php");
?>