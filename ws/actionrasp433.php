<?php
if ( isset($_REQUEST["asxml"]) )
{
	header('Content-Type: text/xml; charset: UTF-8');
	header("Cache-Control: no-cache");
}
$out_xml  = '<rasp433>';
$out_xml .= '<debug>'.$GLOBALS["config"]->general->debug.'</debug>';
// traitement de l'url  pour création de la commande
fn_Trace("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],"xml");
if ( $GLOBALS["config"]->cartes && isset($_REQUEST['rasp433']) )
{
	if ( fn_GetAuth($_SESSION["AuthId"], 'rasp433', $_REQUEST['rasp433']) )
	{
		$rasp433 = new rasp433($_REQUEST['rasp433']);
		$model = fn_GetModel("carte", $rasp433->carteid);
		$carte = new $model($rasp433->carteid);
    $value = $_REQUEST['value'];
		fn_Trace('Clic sur rasp433 '.$_REQUEST['rasp433'],"ihm");
		switch ($rasp433->type)
		{
			Case 'I':
				{
					$carte->fn_SetRasp433($rasp433->numero, $value);
          if ($value=="on")
					{
            if ( $rasp433->messageon == "" )
						  $StatusMsg = fn_GetTranslation('relai_on', $carte->label, $rasp433->label);
					  else
						  $StatusMsg = $rasp433->messageon;
				  }
	  			else
		  		{
			  		if ( $rasp433->messageoff == "" )
					  	$StatusMsg = fn_GetTranslation('relai_off', $carte->label, $rasp433->label);
				  	else
						  $StatusMsg = $rasp433->messageoff;
				  }
        }
				break;
			Case 'ALLON' :
				$carte->setallon();
				$StatusMsg = fn_GetTranslation('relai_all_on', $carte->label);
				break;
			Case 'ALLOFF':
				$carte->setalloff();
				$StatusMsg = fn_GetTranslation('relai_all_off', $carte->label);
				break;
		}

		$StatusMsg = str_replace("%label%", $rasp433->label, $StatusMsg);

		if ( $value == "on" )
			$StatusMsg = str_replace("%etat%", fn_GetTranslation('etat_down'), $StatusMsg);
		else
			$StatusMsg = str_replace("%etat%", fn_GetTranslation('etat_up'), $StatusMsg);
		
    $StatusMsg = str_replace("%carte%", $carte->label, $StatusMsg);
		if ( $rasp433->push )
		{
			if ( $rasp433->push == "on" && $carte->pushautonome != "on" && ( $rasp433->pushon == "all" or ( $rasp433->pushon == "on" and $value == "on" ) or ( $rasp433->pushon == "off" and $value == "off" ) ) )
			{ fn_PushTo($StatusMsg, $rasp433->pushto);
				$StatusMsg .= ' (push)';
			}
		}
	}
	else
	{
		$StatusMsg = fn_GetTranslation('no_rights');
	}
}
fn_Trace('Action '.utf8_encode($StatusMsg),"ihm");
$out_xml .= '<message>'.utf8_encode($StatusMsg);
$out_xml .= '</message>';
$out_xml .= '</rasp433>';
if ( isset($_REQUEST["asxml"]) )
	echo $out_xml;
else
	$_REQUEST["StatusMsg"] = $out_xml;
?>