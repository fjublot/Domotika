<?php
/*----------------------------------------------------------------*
* Gestion des autorisations                                       *
*----------------------------------------------------------------*/
function fn_SetAuth($user_numero, $class, $numero, $value)
{	$doc = new domDocument();
	$doc->preserveWhiteSpace = FALSE;
	$doc->formatOutput = TRUE;
	$doc->load($GLOBALS["config_file"]);

	$xpathdoc= new DOMXPath($doc);
	$List = $xpathdoc->query('//auths');
	if ( $List->length == 0 )
	{
		$List = $xpathdoc->query('//config');
		$List = $List->item(0);
		$New = $doc->createElement('auths');
		$List = $List->appendChild($New);
	}
	else
	{
		$List = $List->item(0);
	}
	$xpath = '//auths/user[@numero='.$user_numero.']';
	$List_fils = $xpathdoc->query($xpath);
	if ( $List_fils->length == 0 )
	{
		$New = $doc->createElement("user");
		$New->setAttribute("numero", $user_numero);
		$List = $List->appendChild($New);
	}
	else
	{
		$List = $List_fils->item(0);
	}
	$xpath = '//user[@numero="'.$user_numero.'"]/'.$class.'[@numero="'.$numero.'"]';
	$List_fils = $xpathdoc->query($xpath);
	if ( $List_fils->length == 0 )
	{
		$New = $doc->createElement($class);
		$New->setAttribute("numero", $numero);
		$New->nodeValue = $value;
		$List = $List->appendChild($New);
	}
	else
	{
		$cible = $List_fils->item(0);
		$taille = strlen($cible->nodeValue);
		$cible->firstChild->deleteData(0,$taille);
		$cible->firstChild->insertData(0,$value);
	}
	$doc->save($GLOBALS["config_file"]);  
}

function fn_InitAuthAllUser($class, $numero)
{
	$xpath = '//users/user';
	$List_user = $GLOBALS["config"]->xpath($xpath);
	if ( count($List_user) != 0 )
	{
		foreach($List_user as $user)
		{
			$user_numero = $user->attributes()->numero;
			$xpath = '//user[@numero="'.$user_numero.'"]/'.$class.'[@numero="'.$numero.'"]';
			$List_fils = $GLOBALS["config"]->xpath($xpath);
			if ( count($List_fils) == 0 )
			{
				if ( $user->privilege >= 90 )
					fn_SetAuth($user_numero, $class, $numero, 'on');
				else
					fn_SetAuth($user_numero, $class, $numero, 'off');
			}
		}
	}
}

function fn_InitAuthUser($user_numero, $privilege = 0)
{
	foreach(array("btn", "an", "cnt", "relai", "camera", "scenario", "scenario", "graphique", "lien", "page", "variable", "vartxt") as $class)
	{
		$xpath = '//'.$class.'s/'.$class;
		$List_fils = $GLOBALS["config"]->xpath($xpath);
		if ( count($List_fils) != 0 )
		{
			foreach($List_fils as $fils)
			{
				if ( $privilege >= 90 )
					fn_SetAuth($user_numero, $class, $fils->attributes()->numero, 'on');
				else
					fn_SetAuth($user_numero, $class, $fils->attributes()->numero, 'off');
			}
		}
	}
}

function fn_DelAuthUser($user_numero)
{
	if ( isset($GLOBALS["config"]) )
	{
		$doc = new  domDocument();
		$doc->preserveWhiteSpace = FALSE;
		$doc->formatOutput = TRUE;
		$doc->load($GLOBALS["config_file"]);

		$xpathdoc= new DOMXPath($doc);
		$List = $xpathdoc->query('//auths');
		$xpath= new DomXPath($doc);
		$query='//user[@numero="'.$user_numero.'"]';
		$nodes = $xpath->query($query);
		foreach ($nodes as $item)
		{
			$item->parentNode->removeChild($item);
		}
		$doc->save($GLOBALS["config_file"]);
	}
}

function fn_GetAuth($user_numero, $class, $numero)
{
	if ( isset($GLOBALS["config"]->auths) )
	{
		$xpath = '//auths/user[@numero="'.$user_numero.'"]/'.$class.'[@numero="'.$numero.'"]';
		$ListAuth = $GLOBALS["config"]->xpath($xpath);
		if ( count($ListAuth) == 1 )
		{
			foreach($ListAuth as $Auths)
			{
				if ( $Auths[0] == 'on' )
					return true;
				else
					return false;
			}
		}
		else
		{
			return false;
		}
	}
	return false;
}

function fn_GetAuthAll($user_numero, $class)
{
	if ( isset($GLOBALS["config"]->auths) )
	{
		$xpath = '//auths/user[@numero="'.$user_numero.'"]/'.$class;
		$ListAuth = $GLOBALS["config"]->xpath($xpath);
		if ( count($ListAuth) != 0 )
		{
			foreach($ListAuth as $Auths)
			{
				if ( $Auths[0] == 'on' )
					return true;
			}
		}
	}
	return false;
}

function fn_DelAuth($class, $numero)
{
	if ( isset($GLOBALS["config"]) )
	{
		$doc = new  domDocument();
		$doc->preserveWhiteSpace = FALSE;
		$doc->formatOutput = TRUE;
		$doc->load($GLOBALS["config_file"]);

		$xpathdoc= new DOMXPath($doc);
		$List = $xpathdoc->query('//auths');
		$xpath= new DomXPath($doc);
		$query='//'.$class.'[@numero="'.$numero.'"]';
		$nodes = $xpath->query($query);
		foreach ($nodes as $item)
		{
			$item->parentNode->removeChild($item);
		}
		$doc->save($GLOBALS["config_file"]);  
	}
}
?>