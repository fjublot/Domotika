<?php
/*----------------------------------------------------------------*
* Titre : vartxt.php                                              *
* Classe de variable texte   09/02/2014                           *
*----------------------------------------------------------------*/
require_once($GLOBALS["mvc_path"]."top.php");
class vartxt extends top
{
//	public $type;
	public $carteid, $suffixe, $update_time, $xmlid;
	public function __construct($numero = "", $info = null)
	{
		parent::__construct($numero, $info);
		if ( ! isset($this->label) )
		{
			$this->label = 'Variable texte perso';
			$this->suffixe = '';
			$this->xmlid = "";
			if ( preg_match("/^carte-([0-9]*)-".get_class($this)."-(.*)$/", $this->numero, $regs) )
			{
				$this->carteid = $regs[1];
			}
			else
			{
				$this->carteid = "";
			}
		}
		return $this;
	}
	public function del()
	{
		fn_DelAuth(get_class($this), $this->numero);
		return parent::del();
	}
	public function form($page=null)
	{
		$return  =  fn_HtmlStartPanel(fn_GetTranslation(__class__), $this->label, __class__, $this->numero);
		$return .= '			<form name="ajaxform" id="ajaxform" class="form-horizontal form-label-left">';
		if (isset($_REQUEST["HTTP_REFERER"]))
			$return .= '			<input type=hidden name=HTTP_REFERER value="' . urlencode($_REQUEST["HTTP_REFERER"]) . '">';
  		elseif (isset($_SERVER["HTTP_REFERER"]))
  			$return .=  '			<input type=hidden name=HTTP_REFERER value="' . urlencode($_SERVER["HTTP_REFERER"]) . '">';
  		else
  			$return .=  '			<input type=hidden name=HTTP_REFERER value="">';

		$return .=  '			<input type=hidden name=class value="' . __class__ . '">';
        if (isset($this->numero))
  			$return .=  '			<input type=hidden name="numero" value="' . $this->numero . '">';
		$return .= fn_HtmlInputField('label', $this->label, 'text', 'variable_name', 'variable.label', '');
		$return .= fn_HtmlInputField('suffixe', $this->suffixe, 'text', 'suffixe', 'variable.suffixe', '');
  		return $return;
	}
	public function receive_form($list_data = null)
	{
		list($status, $message) = parent::receive_form(array("label", "suffixe"));
		return array($status, $message);
	}
	public function save($list_data = null)
	{
		if ( ! isset($this->numero) || $this->numero == "" )
			$new = true;
		else
			$new = false;
		$return = parent::save(array("label", "suffixe", "carteid", "xmlid"));
		fn_InitAuthAllUser(get_class($this), $this->numero);
		return $return;
	}
	public function verif_before_del()
	{
		return true;
	}
	public function disp($commandactive=true, $jsremove=false) {
		$classcss='noteditable';
		if ($jsremove)
			$classcss='';
		$return = '<div class="Container animated flipInY ' . $GLOBALS["config"]->csscontainer .' ' .  $classcss.'" data-id="'.$this->config_class.'_'.$this->numero.'">';
		$return.=    '<div class="Entete '.get_class($this).'_label_'.$this->numero.'" id="'.get_class($this).'_label_'.$this->numero.'">'.$this->label.'</div>';
		$return.=    '<div class="DispValue"><div class="Valeur '.get_class($this).'_valeur_'.$this->numero.'" id="'.get_class($this).'_valeur_'.$this->numero.'">'.$this->valeur.'</div></div>';
		$return.=    '<div class="Caption '.get_class($this).'_suffixe_'.$this->numero.'" id="'.get_class($this).'_suffixe_'.$this->numero.'">'.$this->suffixe.'</div>';
		$return.='<i class="js-remove fa fa-trash-o fa-2x"></i>';	
		$return.='</div>';

  	return $return;
	}
	public function asxml($detail = false)
	{
		if ( $this->carteid == "" )
			$this->mysql_load();
		$return = "<".get_class($this)." numero='".$this->numero."'>";
		$return .= '<valeur>'.$this->valeur.'</valeur>';
		if ( $detail )
		{
			$return .= "<label>".$this->label."</label>";
			$return .= "<suffixe>".$this->suffixe."</suffixe>" ;
		}
		$return .= "</".get_class($this).">";
		return $return;
	}
	public function update()
	{
		if ( ! isset($this->suffixe) )
			$this->suffixe = "";
		if ( ! isset($this->carteid) )
			$this->carteid = "";
		if ( ! isset($this->xmlid) )
			$this->xmlid = "";
		parent::update();
	}
  public function js()
  {
		$return ='';
    return $return;
  }
}
?>