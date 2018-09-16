<?php
/*----------------------------------------------------------------*
* Titre : sep.php                                            *
* Classe de sep                                              *
*-----------------------------------------------------------------*/
require_once($GLOBALS["mvc_path"]."top.php");
class sep extends top
{
	public function __construct($numero = "", $info = null) {
		parent::__construct($numero, $info);
	}
	public function disp($hr=true, $jsremove=false) {

		if ($hr==true)
			$return= '<hr />';
		else {
			$classcss='noteditable';
			if ($jsremove)
				$classcss='';
			$return  = '<div class="Container animated flipInY col-lg-1 col-md-1 col-sm-2 col-xs-12 Pointer '.$classcss.'" data-id="'.$this->config_class.'_'.$this->numero.'" style="width:100%;">';
			$return .= '<hr style="visibility:visible;color:black;"> ';
			$return .='<i class="js-remove fa fa-trash-o fa-2x"></i>';	
			$return .= '</div>';
		}
		return $return;
	}
	
	public function disp_li($first=null) {
		return '<li class="Separateur">Separateur</li>';
	}
}
?>