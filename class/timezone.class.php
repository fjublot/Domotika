<?php
/*----------------------------------------------------------------*
* Titre : timezone.php                                            *
* Classe de timezone                                              *
*-----------------------------------------------------------------*
* Créé par    : Thomas            Le : 14/11/2011  Version : 1.00 *
* Modifié par : XXXXXXXX          Le : XX/XX/XXXX  Version : 1.01 *
*-----------------------------------------------------------------*/
require_once($GLOBALS["mvc_path"]."top.php");
class timezone extends top
{
	public function save($list_data = null)
	{
		return parent::save(array("label"));
	}
}
?>