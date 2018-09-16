<?php
/*----------------------------------------------------------------*
* Titre : unite.php                                            *
* Classe de unite                                              *
*----------------------------------------------------------------*/
require_once($GLOBALS["mvc_path"]."top.php");
class unite extends top
{
	public $unit; 
	public function __construct($numero="", $info = null)
	{
		parent::__construct($numero, $info);
		if ( ! isset($this->label) ) {
			$this->label = 'Unité perso';
		}
		
		return $this;
	}
	public function del()
	{
		fn_DelAuth(get_class($this), $this->numero);		return parent::del();
	}
	public function form($page=null)
	{
		$return  =  fn_HtmlStartPanel(fn_GetTranslation(__class__), $this->label, __class__, $this->numero);
		$return .= '		<div class="x_content">';
		$return .= '			<form class="form-horizontal form-label-left">';
		if (isset($_REQUEST["HTTP_REFERER"]))
			$return .= '			<input type=hidden name=HTTP_REFERER value="' . urlencode($_REQUEST["HTTP_REFERER"]) . '">';
  		elseif (isset($_SERVER["HTTP_REFERER"]))
  			$return .=  '			<input type=hidden name=HTTP_REFERER value="' . urlencode($_SERVER["HTTP_REFERER"]) . '">';
  		else
  			$return .=  '			<input type=hidden name=HTTP_REFERER value="">';
  		$return .=  '			<input type=hidden name="class" value="' . __class__ . '">';
        
		if (isset($this->numero))
  			$return .=  '			<input type=hidden name="numero" value="' . $this->numero . '">';
		$return .= fn_HtmlInputField('unit', $this->unit, 'text', 'unit_unit', 'unite.unit', '');
		$return .= fn_HtmlInputField('label', $this->label, 'text', 'unit', 'unite.label', '');
  		return $return;
	}
	public function receive_form($list_data = null)
	{
		list($status, $message) = parent::receive_form(array("label", "numero", "unit"));
		return array($status, $message);
	}
	public function save($list_data = null)
	{
		$return = parent::save(array("label", "unit"));
		return $return;
	}
	public function verif_before_del()
	{
		return true;
	}
	public function update()
	{
	}
  public function js()
  {
  }
	public function disp_list() {
				$return  = '<a href="?app=Mn&page=Add&class='.__class__.'&numero='.$this->numero.'" class="'. $GLOBALS["classDispList"] .' btn-app">';
				$return .= '	<div class="well profile_view">';
				$return .= '		<div class="col-sm-12">';
				$return .= '			<h2 class="col-sm-10 left brief searchable filterable" data-beginletter="'.strtoupper(substr($this->label,0,1)).'"><span class="badge bg-red">'.$this->numero.'</span>&nbsp;'.$this->label.'</h2>';
				$return .= '		</div>';
				$return .= '	</div>';
				$return .= '</a>';
				echo $return;
			//}
	}
  
}
?>