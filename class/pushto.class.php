<?php
/*----------------------------------------------------------------*
* Titre : pushto.php                                              *
* Classe de pushto                                                *
*----------------------------------------------------------------*/
require_once($GLOBALS["mvc_path"]."top.php");
class pushto extends top
{
	public $password, $signature, $actif, $timezone, $type, $moreinfo;
	public function __construct($numero, $info = null) {
		parent::__construct($numero, $info);
		return $this;
	}
	public function form($page=null) {
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

 		$return .= fn_HtmlSelectField('type', 'type', 'pushto.type',"",false,true);
		$return .= fn_HtmlInputField('label', $this->label, 'text', 'compte', 'pushto.label', '');
 		$return .= fn_HtmlInputField('password', $this->password, 'text','password', 'pushto.password', '');
 		$return .= fn_HtmlInputField('signature', $this->signature, 'text','signature', 'pushto.signature', '');
 		$return .= fn_HtmlInputField('moreinfo', $this->moreinfo, 'text','more_info', 'pushto.moreinfo', '');
		$return .= fn_HtmlBinarySelectField('actif', $this->actif, 'activer_push', 'btn.actif');
 		$return .= fn_HtmlSelectField('timezone', 'time_zone', 'pushto.timezone',"",false,true);
		$return .= '<div class="form-group">';
		$return .= '	<label class="control-label control-label col-md-3 col-sm-3 col-xs-12" for="BT_PushToTest">';
		$return .= '		<span class="span-label"></span>';
		$return .= '	</label>';
		$return .= fn_Help('pushto.testenvoi');
		$return .= '	<div id="input-btns" class="col-md-6 col-sm-6 col-xs-12">';
		$return .= '		<button id="BT_PushToTest" class="btn btn-primary" onclick="jQuery(\'#action\').val(this.id);" name="BT_PushToTest" type="">'.ucfirst(fn_GetTranslation('test_compte')).'</button>';
		$return .= '	</div>';
		$return .= '</div>';		
		return $return;
	}
	public function js() {
		$return='AjaxLoadSelectJson("type", "class=typepushto", false, "'.$this->type.'" );
			AjaxLoadSelectJson("timezone", "class=timezone", false, "'.$this->timezone.'" );';
		return $return;
	}
	public function receive_form($list_data = null) {
		list($status, $message) = parent::receive_form(array("type", "label", "password", "signature", "actif", "timezone", "moreinfo"));
		return array($status, $message);
	}
	public function save($list_data = null) {
		return parent::save(array("type", "label", "password", "signature", "actif", "timezone", "moreinfo"));
	}
	public function send_message($message, $event='') {
		if ( $this->actif == "on" ) {
			require_once($GLOBALS["class_path"]."external/send_".$this->type.".class.php");
			if ( $this->timezone != "" )
				date_default_timezone_set($this->timezone);
			if ( $event == '' )
				$event = $GLOBALS["config"]->general->nameappli;
			$fct = "send_message_".$this->type;
			$url = "";
			$retour_send_message=$fct($this->label, $this->password, $this->signature, $event, $message, $this->moreinfo, $GLOBALS["config"]->general->url);
			fn_Trace($this->type.' - '.$retour_send_message[1].' - '.$fct.'('.$this->label.', '.$this->password.', '.$this->signature.', '.$event.', '.$message.', '.$this->moreinfo.', '.$GLOBALS["config"]->general->url.')', 'PushtTo');
			return $retour_send_message;
		}
		else
			return array(true, "Notification désactivée");
	}
	public function update() {
		if ( !isset($this->more_info) )
			$this->more_info = "";
	}
	public function disp_list() {
		$checked="";
		if ($this->actif=="on") $checked="checked";
		$xpath = "//typepushtos/typepushto[@numero='".$this->type."']";
		$type = fn_GetByXpath($xpath, 'bal', 'label');		
		$return  = '<a href="?app=Mn&amp;page=Add&amp;class=' . __class__ . '&amp;numero=' . $this->numero . '&amp;del=true" class="'. $GLOBALS["classDispList"] .'">';
		$return .= '	<div class="well profile_view" style="min-height:160px;">';
		$return .= '		<div class="col-sm-12">';
		$return .= '			<h2 class="col-sm-10 left brief searchable filterable" data-beginletter="'.strtoupper(substr($this->label,0,1)).'"><span class="badge bg-red">'.$this->numero.'</span>&nbsp;'.$this->label.'</h2>';
		$return .= '			<p><input type="checkbox" data-toggle="toggle" data-size="small" disabled  data-style="pull-right" '.$checked.' ></p>';
		$return .= '			<div class="left col-xs-8">';
		$return .= '				<p class="searchable">'.fn_GetTranslation("type").' : '.$type.'</p>';
		$return .= '			</div>';
		$return .= '			<div class="right col-xs-4 text-center">';
		$return .= '				<img src="images/pushtos/'.$this->type.'.jpg" alt="" class="img-circle img-responsive">';
		$return .= '			</div>';
		$return .= '		</div>';
		$return .= '	</div>';
		$return .= '</a>';
		echo $return;
	}

}
?>