<?php
/*----------------------------------------------------------------*
* Titre : variable.php                                            *
* Classe de variable                                              *
*----------------------------------------------------------------*/
require_once($GLOBALS["mvc_path"]."top.php");
class variable extends top
{
//	public $type;
	public $unite, $update_time, $precision;
	public function __construct($numero = "", $info = null)
	{
		parent::__construct($numero, $info);
		if ( ! isset($this->label) )
		{
			$this->label = 'Variable perso';
			$this->precision = '';
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
		$return  =  fn_HtmlStartPanel($this->label, ucfirst(fn_GetTranslation(__class__)), __class__,$this->numero);
		$return .= '<form name="ajaxform" id="ajaxform" class="form-horizontal form-label-left">';
		$return .= fn_HtmlHiddenField('class',__class__);
		if (isset($this->numero)) {
			$return .=  fn_HtmlHiddenField('numero',$this->numero);
		}
	   	$return .= fn_HtmlHiddenField('action','');
		$return .= fn_HtmlInputField('label', $this->label, 'text', 'variable_name', 'variable.label', '');
		$return .= fn_HtmlSelectField('unite', 'unite', 'variable.unite',"",false,true);
		$return .= fn_HtmlInputField('precision', $this->precision, 'text', 'precision', 'variable.precision', '');
  	return $return;
	}
	public function receive_form($list_data = null)
	{
		list($status, $message) = parent::receive_form(array("label", "unite", "precision"));
		return array($status, $message);
	}
	public function save($list_data = null)
	{
		if ( ! isset($this->numero) || $this->numero == "" )
			$new = true;
		else
			$new = false;
		$return = parent::save(array("label", "unite", "precision"));
		fn_InitAuthAllUser(get_class($this), $this->numero);
		return $return;
	}
	public function verif_before_del()
	{
		return true;
	}
	public function disp($commandactive=true, $jsremove=false) {
		$classcss='noteditable';
		$xpathModele='//unites/unite[@numero="'.$this->unite.'"]';
		$unite = $GLOBALS["config"]->xpath($xpathModele);
		$unite = addcslashes($unite[0]->{'label'}, "'");
		
		if ($jsremove)
			$classcss='';
		$return = '<div class="Container animated flipInY ' . $GLOBALS["config"]->csscontainer .' ' .  $classcss.'" data-id="'.$this->config_class.'_'.$this->numero.'">';
		$return.=    '<div class="Entete '.get_class($this).'_label_'.$this->numero.'">'.$this->label.'</div>';

		$return.=    '<div class="DispValue">';
		$return.=    	'<div class="Valeur">';
		$return.=    		'<span class="'.get_class($this).'_valeur_'.$this->numero.'" >'.$this->valeur.'</span>';
		$return.=       	'<div class="UnitVariable">';
		$return.=       		'<span class="'.get_class($this).'_unit_'.$this->numero.'">'.$unite.'</span>';
		$return.=       	'</div>';
		$return.= 	 	'</div>';
		$return.= 	 '</div>';
		$return.=    '<div class="Caption '.get_class($this).'_txt_'.$this->numero.' Visibility-hidden"></div>';
		$return.='<i class="js-remove fa fa-trash-o fa-2x"></i>';	
		$return.= '</div>';
		return $return;
	}
	public function asxml($detail = false)
	{
		$xpathModele='//unites/unite[@numero="'.$this->unite.'"]';
		$unite = $GLOBALS["config"]->xpath($xpathModele);
		$this->mysql_load();
		$return = "<".get_class($this)." numero='".$this->numero."'>";
		if ( $this->precision != "" )
			$this->valeur = number_format((float) $this->valeur, (int) $this->precision, '.', '');
		$return .= '<valeur>'.$this->valeur.'</valeur>';
		if ( $detail )
		{
			$return .= "<label>".$this->label."</label>";
			$return .= "<unite>".$unite[0]->{'label'}."</unite>" ;
		}
		$return .= "</".get_class($this).">";
		return $return;
	}
	public function update()
	{
		if ( ! isset($this->unite) || $this->unite == "" )
			$this->unite = "sans";
		if ( ! isset($this->precision) )
			$this->precision = "";
		parent::update();
	}
  public function js()
  {
		$return ='AjaxLoadSelectJson("unite", "class=unite", false, "'.$this->unite.'" );';
    return $return;
  }
	public function disp_list() {
		$return  = '<a href="?app=Mn&amp;page=Add&amp;class=' . __class__ . '&amp;numero=' . $this->numero . '&amp;del=true" class="'. $GLOBALS["classDispList"] .'">';
		$return .= '	<div class="well profile_view">';
		$return .= '		<div class="col-xs-12">';
		$return .= '			<div>';
		$return .= '                <span class="col-xs-9 left brief searchable filterable" style="margin-top: 4px;" data-beginletter="'.strtoupper(substr($this->label,0,1)).'">';
		$return .= '					<span class="h2"><span class="badge bg-red">'.$this->numero.'</span>';
		$return .= '					'.$this->label.'</span>';
		$return .= '					</span>';
		$return .= '            </div>';
		$return .= '			<div class="left col-xs-12">';
		$return .= '				<p class="searchable">'.ucfirst(fn_GetTranslation("unit")).' : '.$this->unite.'</p>';
		$return .= '			</div>';
		$return .= '			<div class="right col-xs-4 text-center">';
		//$return .= '				<img src="'.$this->picto.'" alt="" class="img-circle img-responsive">';
		$return .= '			</div>';
		$return .= '		</div>';
		$return .= '	</div>';
		$return .= '</a>';
		echo $return;
	}
	
	public function GetState()
	{	
		$Msg = "";
			$this->mysql_load();
			if ( $this->precision != "" )
				$this->valeur = number_format((float) $this->valeur, (int) $this->precision, '.', '');
			return $this->valeur;
	}	

// asjson
	// Renvoi un tableau du status du cnt pouvant être encodé en json 
	public function asjson($detail = false) {
		$xpathModele='//unites/unite[@numero="'.$this->unite.'"]';
		$unite = $GLOBALS["config"]->xpath($xpathModele);
		$this->mysql_load();
		if ( $detail )
			$variablejson = array('numero' => $this->numero, 'value' => $this->valeur);
		else
			$variablejson = array('numero' => $this->numero, 'label' => $this->label, 'value' => $this->valeur, 'unite' => $unite[0]->{'label'});
		
		return $variablejson;
	}
	

}
?>