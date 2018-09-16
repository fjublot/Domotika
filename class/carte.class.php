<?php
/*----------------------------------------------------------------*
* Titre : carte.php                                               *
* Classe de carte                                                 *
*-----------------------------------------------------------------*/
class carte extends top
{
	public $active, $ip, $port, $timezone, $model;
	public $creation;
	public function __construct($numero="", $info = null)
	{
		parent::__construct($numero, $info);
		$this->creation = false;
		if ( ! isset($this->model) )
		{   
			$this->creation = true;
		}
		$this->status = false;
		return $this;
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function del()
	{
		$xpath = "//*[carteid='".$this->numero."']";
		$nodes = $GLOBALS["config"]->xpath($xpath);
		if ( count($nodes) != 0 )
		{
			foreach ($nodes as $item)
			{
				$class = $item->getName();
				$current = new $class((string)$item->attributes()->numero, $item);
				$current->del();
			}
		}
		return parent::del();
	}
	public function form($page=null)
	{
		$return  =  fn_HtmlStartPanel($this->label, ucfirst(fn_GetTranslation(__class__)), __class__, $this->numero);
		$return .= '<form name="ajaxform" id="ajaxform" class="form-horizontal form-label-left" novalidate>';
		if (isset($_REQUEST["HTTP_REFERER"]))
			$return .= fn_HtmlHiddenField ('HTTP_REFERER',urlencode($_REQUEST["HTTP_REFERER"]));
  			elseif (isset($_SERVER["HTTP_REFERER"]))
  				$return .=  fn_HtmlHiddenField('HTTP_REFERER',urlencode($_SERVER["HTTP_REFERER"]));
  			else
  				$return .=  fn_HtmlHiddenField('HTTP_REFERER', '');
  			$return .=  fn_HtmlHiddenField('class',__class__);
        
			if (isset($this->numero)) {
  				$return .=  fn_HtmlHiddenField('numero',$this->numero);
			}
		$return .= fn_HtmlHiddenField('action','');
		$return .= fn_HtmlInputField('label', $this->label, 'text', 'carte_name', 'carte.label', '',true);
		if ($this->creation)
			$return .=  fn_HtmlSelectField('model', 'carte_model', 'carte.model',"",false,false,false);
		else {
			$return .= fn_HtmlSelectField('disablemodel', 'carte_model', 'carte.model',"",false,false,true);
			$return .= fn_HtmlHiddenField('model',$this->model);
		}
			$return .= fn_HtmlBinarySelectField('active', $this->active, 'active', 'carte.active');
		$return .= fn_HtmlInputField('ip', $this->ip, 'text', 'ip_address', 'carte.ip', "", true);
		if ($this->port=="")
			$port='80';
		else
			$port=$this->port;
		$return .= fn_HtmlInputField('port', $port, 'text', 'port', 'carte.port', "", false);
 		$return .= fn_HtmlSelectField('timezone', 'time_zone', 'carte.timezone',"",false,true);
		return $return;
	}
	public function js()
	{
    //if ( ! isset($this->model) || $this->model == "" )
		if ($this->creation)
			$return ='AjaxLoadSelectJson("model", "class=modelcarte", false, "'.$this->model.'" );'.PHP_EOL;
		else
			$return ='AjaxLoadSelectJson("disablemodel", "class=modelcarte", false, "'.$this->model.'" );'.PHP_EOL;
		$return .='AjaxLoadSelectJson("timezone", "class=timezone", false, "'.$this->timezone.'" );'.PHP_EOL;
		$return .='AjaxUpdateImgValid("?app=Ws&page=verifxml&type=ip&ip='.$this->ip.'", "ip");'.PHP_EOL;
		$return .='AjaxUpdateImgValid("?app=Ws&page=verifxml&type=port&port='.$this->port.'", "port");'.PHP_EOL;
		return $return;
	}
	public function advancedobject() {
		$return  = fn_HtmlStartPanel(fn_GetTranslation('management',fn_GetTranslation('object').'s'), ucfirst(fn_GetTranslation(__class__)).' '.$this->label, '', 'none').PHP_EOL;
		$return .= '<div class="x_content" style="display: block;">'.PHP_EOL;
		foreach($this->subclass as $classitem) {
			$return .= '<a class="animated fadeInDown btn-app btn" href="?app=Mn&amp;page=List&amp;class='.$classitem.'&amp;liens=1&amp;filtre_carteid='.$this->numero.'&amp;addnew=true'.PHP_EOL;
           	if ( isset($_REQUEST["http_referer"]) )
           		$return .= '&http_referer='.urlencode($_REQUEST["http_referer"]).PHP_EOL;
			$return .= '">';
			if ( isset($GLOBALS["config"]->{$classitem."s"})) {
				$xpath = "//".$classitem."[carteid='".$this->numero."']";
				$nodes = $GLOBALS["config"]->xpath($xpath);
				$return .= '<span class="badge bg-red">'.count($nodes).'</span>'.PHP_EOL;
			}
			else
				$return .='<span class="badge bg-red">0</span>'.PHP_EOL;
			$return .= '<i class="fa ' . fn_GetTranslation('fa-'.$classitem) . '"></i>'.PHP_EOL;
			$return .= ucfirst(fn_GetTranslation($classitem)).'s'.PHP_EOL;
			$return .= '</a>'.PHP_EOL;
		}
		$return .= '</div>'.PHP_EOL;
		return $return;
	}
	
	public function receive_form($list_data = array())
	{
		$list_data = array_merge($list_data, array("label", "active", "ip", "port", "timezone", "model"));
		list($status, $message) = parent::receive_form($list_data);
		$this->update();
		if ( $this->port == "80" )
			$this->port = "";
		if ( $this->active=="")
			$this->active="off";

		return array($status, $message);
	}
	public function save($list_data = array())
	{
		$list_data = array_merge($list_data, array("label", "active", "ip", "port", "timezone", "model"));
		$new = false;
		if ( ! isset($this->numero) || $this->numero == "" )
			$new = true;
		$return = parent::save($list_data);
		return $return;
	}

	public function required_field()
	{
		return array_key_exists("model", $_POST);
		//return true;
	}
	
	public function verif_before_del()
	{
		return parent::verif_before_del();
	}
	public function geturl($withpassword = true)
	{
		$url = 'http://';
		$url .= $this->ip;
		if ( $this->port != '' )
		{
			$url .= ':'.$this->port;
		}
		return $url."/";
	}
	public function get_status()
	{
		if ( $this->active != 'on' )
		{
			$this->status = false;
			return false;
		}
		return $this->status;
	}
	public function update()
	{
		if ( ! isset($this->active) )
			$this->active = "on";
		if ( isset($this->version) )
			unset($this->version);
		/*if ( ! isset($this->model) )
		{
			$xpath = "//variable[carteid='".$this->numero."']";
			$nodes = $GLOBALS["config"]->xpath($xpath);
			if ( count($nodes) != 0 )
			{
				$this->model = "ecodev";
			}
			else
			{
				$this->model = "ipx800";
			}
		}
		*/
		parent::update();
	}
			public function disp_list() {
				$checked="";
				$xpath = "//modelcartes/modelcarte[@numero='".$this->model."']";
				$modelcarte = fn_GetByXpath($xpath, 'bal', 'label');			
				if ($this->active=="on") $checked="checked";
				$return  = '<a href="?app=Mn&amp;page=Add&amp;class=' . __class__ . '&amp;numero=' . $this->numero . '&amp;del=true" class="'. $GLOBALS["classDispList"] .'">';
				$return .= '	<div class="ribbon blue"><span>'.$this->model.'</span></div>';
				$return .= '	<div class="well profile_view">';
				$return .= '		<div class="col-xs-12">';
				$return .= '			<div>';
				$return .= '                <span class="col-xs-9 left brief searchable filterable" style="margin-top: 4px;" data-beginletter="'.strtoupper(substr($this->label,0,1)).'">';
				$return .= '					<span class="badge bg-red">'.$this->numero.'</span>';
				$return .= '					<span class="h2">'.$this->label.'</span>';
				$return .= '					</span>';
				$return .= '			        <input type="checkbox" data-toggle="toggle" data-size="small" disabled  data-style="pull-right" '.$checked.' >';
				$return .= '            </div>';
				$return .= '			<div class="left col-xs-12">';
				$return .= '				<p class="searchable">'.ucfirst(fn_GetTranslation("carte_model"))." : ".$modelcarte.'</p>';
				
				foreach($this->subclass as $sousclass) {
					$xpath = "//".$sousclass."[carteid='".$this->numero."']";
					$badge = fn_GetByXpath($xpath, 'fn', 'count');
					$return .= '<p class="searchable"><span class="badge col-xs-2">'.$badge.'</span>&nbsp;'.ucfirst(fn_GetTranslation($sousclass)).'(s)</p>';
				}
				$return .= '			</div>';
				
				//$return .= '			<div class="right col-xs-4 text-center">';
				//$return .= '				<img src="images/cartes/'.$this->model.'.png" alt="" class="img-circle img-responsive">';
				//$return .= '			</div>';
				
				$return .= '		</div>';
				$return .= '	</div>';
				$return .= '</a>';
				//$return .= '            <div class="corner-ribbon bottom-right sticky green shadow">Hello</div>';
				echo $return;
			}
	
}
?>