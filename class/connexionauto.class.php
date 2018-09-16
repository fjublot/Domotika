<?php
/*----------------------------------------------------------------*
* Titre : connexionauto.php                                       *
* Classe de connexionauto                                         *
*-----------------------------------------------------------------*/
class connexionauto extends top
{
	public $ip, $compte, $actif;
	public function __construct($numero ="", $info = null)
	{
		parent::__construct($numero, $info);
		return $this;
	}
	public function form($page=null)
	{
		$return  =  fn_HtmlStartPanel($this->label, fn_GetTranslation(__class__), __class__, $this->numero);
		$return .= '		<div class="x_content">';
		$return .= '			<form name="ajaxform" id="ajaxform" class="form-horizontal form-label-left">';
		if (isset($_REQUEST["HTTP_REFERER"]))
			$return .= fn_HtmlHiddenField ('HTTP_REFERER',urlencode($_REQUEST["HTTP_REFERER"]));
  			elseif (isset($_SERVER["HTTP_REFERER"]))
  				$return .=  fn_HtmlHiddenField('HTTP_REFERER',urlencode($_SERVER["HTTP_REFERER"]));
  			else
  				$return .=  fn_HtmlHiddenField('HTTP_REFERER', '');
  		$return .=  fn_HtmlHiddenField('class',__class__);
		if (isset($this->numero)) 
			$return .=  fn_HtmlHiddenField('numero',$this->numero);
		$return .= fn_HtmlHiddenField('action','');
		$return .= fn_HtmlInputField('label', $this->label, 'text', 'connexionauto_name', 'connexionauto.label', "",true, "");
		$return .= fn_HtmlBinarySelectField('actif', $this->actif, 'connexionauto_actif', 'connexionauto.actif');
		$return .= fn_HtmlInputField('ip', $this->ip, 'text', 'ip_address', 'connexionauto.ip', "",false);
 		$return .= fn_HtmlSelectField('compte', 'login', 'connexion.compte',"",false,false);
		return $return;
	}
	public function js()
	{
		$return='AjaxLoadSelectJson("compte", "class=user", false, "'.$this->compte.'" );';
				$return .='/* Règles de validation */
				var configvalidate = {
					// ordinary validation config
					form : \'#ajaxform\',
					// reference to elements and the validation rules that should be applied
					validate : {
						\'#label\' : {
						    validation : \'length\',
							length : \'1-20\'
						},
					}
				};
		'.PHP_EOL;
		return $return;
	}
	public function receive_form($list_data = null)
	{
		list($status, $message) = parent::receive_form(array("label", "ip", "compte", "actif"));
		return array($status, $message);
	}
	public function save($list_data = null)
	{
		return parent::save(array("label", "ip", "compte", "actif"));
	}
	public function update()
	{
		if ( ! isset($this->actif) )
			$this->actif = "1";
	}
	public function jsfields($list_data = null)
	{
		return parent::jsfields(array("label", "ip", "compte", "actif"));
	}
	public function urllistfields($list_data = null)
	{
		return parent::urllistfields(array("label", "ip", "compte", "actif"));
	}
	public function disp_list() {
		$checked="";
		$xpath = '//users/user[@numero = "'.$this->compte.'"]';
		$labeluser = fn_GetByXpath($xpath, 'bal', 'label');
		if ($this->actif=="on") $checked="checked";
		$return  = '<a href="?app=Mn&amp;page=Add&amp;class=' . __class__ . '&amp;numero=' . $this->numero . '&amp;del=true" class="'. $GLOBALS["classDispList"] .'">';
		$return .= '	<div class="well profile_view">';
		$return .= '		<div class="col-sm-12">';
		$return .= '			<h2 class="col-sm-10 left brief searchable filterable" data-beginletter="'.strtoupper(substr($this->label,0,1)).'"><span class="badge bg-red">'.$this->numero.'</span>&nbsp;'.$this->label.'</h2>';
		$return .= '			<p><input type="checkbox" data-toggle="toggle" data-size="small" disabled  data-style="pull-right" '.$checked.' ></p>';
		$return .= '			<div class="left col-xs-8">';
		$return .= '				<p class="searchable">'.fn_GetTranslation("ip_address").' : '.$this->ip.'</p>';
		$return .= '				<p class="searchable">'.fn_GetTranslation("login").' : '.$labeluser.'</p>';
		$return .= '			</div>';
		$return .= '			<div class="right col-xs-4 text-center">';
		//$return .= '				<img src="'.$this->picto.'" alt="" class="img-circle img-responsive">';
		$return .= '			</div>';
		$return .= '		</div>';
		$return .= '	</div>';
		$return .= '</a>';
		echo $return;
	}
}
?>