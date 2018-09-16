<?php
/*----------------------------------------------------------------*
* Titre : relai.php                                               *
* Classe de relai                                                 *
*----------------------------------------------------------------*/

class rasp433 extends top
{
	public $active, $type, $imageon, $messageon, $msgpushon, $imageoff, $messageoff, $msgpushoff, $push, $pushon, $activationscenario, $deactivationscenario,  $carteid, $xmlid;
	public $pushto = array();
	
	public function __construct($numero = "", $info = null)
	{
		parent::__construct($numero, $info);
		if ( ! isset($this->type) )
		{
			$this->messageon = "%rasp433% - %etat%";
			$this->messageoff = "%rasp433% - %etat%";
			$this->msgpushon = "%rasp433% %etat%";
			$this->msgpushoff = "%rasp433% %etat%";
			$this->message = "%rasp433% - %etat%";
			$this->push = "0";
      $this->label = 'Prise '.$numero;
			$this->active = 1;
			$this->pushon = "all";
      $this->type="I";
		}
    
    if ( file_exists($GLOBALS["root_path"]."/config/images/".$this->imageon) )
			$this->imageon = $GLOBALS["root_path"]."/config/images/".$this->imageon;
		else
			$this->imageon = $GLOBALS["root_path"].'images/relais/'.$this->imageon;
		
    if ( file_exists($GLOBALS["root_path"]."/config/images/".$this->imageoff) )
			$this->imageoff = $GLOBALS["root_path"]."/config/images/".$this->imageoff;
		else
			$this->imageoff = $GLOBALS["root_path"].'images/relais/'.$this->imageoff;

    //$this->valeur=$this->getstate();
		return $this;
	}

	public function del()
	{
		fn_DelAuth(get_class($this), $this->numero);
		return parent::del();
	}
	
	public function form($page=null)
	{
 		$return  = fn_HtmlInputField('label', $this->label, 'text', 'radio_name', 'rasp433.label', '');
    $return .= fn_HtmlBinarySelectField('active', $this->active, 'active', 'rasp433.active');
    $return .= fn_HtmlBinarySelectField('push', $this->push, 'send_push', 'rasp433.push');
 		$return .= fn_HtmlSelectField('pushon', 'push_on', 'rasp433.pushon','',false);
 		$return .= fn_HtmlSelectField('pushto', 'push_account', 'rasp433.pushto','',true);
 		$return .= fn_HtmlSelectField('type', 'type', 'relai.type','',false); 
 		$return .= fn_HtmlSelectField('imageon', 'imageon', 'relai.imageon',"",false,true);
		$return .= fn_HtmlInputField('messageon', $this->messageon, 'text', 'message_on', 'rasp433.messageon', '');
		$return .= fn_HtmlInputField('msgpushon', $this->msgpushon, 'text', 'msgpush_on', 'rasp433.msgpushon', '');
 		$return .= fn_HtmlSelectField('imageoff', 'imageoff', 'relai.imageoff',"",false,true);
    $return .= fn_HtmlInputField('messageoff', $this->messageoff, 'text', 'message_off', 'rasp433.messageoff', '');
		$return .= fn_HtmlInputField('msgpushoff', $this->msgpushoff, 'text', 'msgpush_off', 'rasp433.msgpushoff', '');
		$return .= '<script src="ressources/previewimage/jquery.previewimage.js"></script>';
		return $return;
	}

  public function js()
  { 	          //AjaxLoadSelectJson("#pushto", "class=pushto", true, "'.$this->pushto.'" );
    $return='
            AjaxLoadSelectJson("pushon", "class=pushon_type", false, "'.$this->pushon.'" );
   	        AjaxLoadSelectJson("pushto", "class=pushto", true, "'.$this->pushto.'" );
            AjaxLoadSelectJson("type", "class=typerasp433", false, "'.$this->type.'" );
            AjaxLoadSelectJson("deactivationscenario", "class=scenario", true, "'.$this->deactivationscenario.'" );
	          AjaxLoadSelectJson("activationscenario", "class=scenario", true, "'.$this->activationscenario.'" );
  	        AjaxLoadSelectJson("imageon", "dossier=relais", false, "'.$this->imageon.'" );
	          AjaxLoadSelectJson("imageoff", "dossier=relais", false, "'.$this->imageoff.'" );
            $(\'.previewimage\').preimage();

	          ';
  return $return;
  }
	public function receive_form($list_data = null)
	{
		list($status, $message) = parent::receive_form(array("label", "type", "imageon", "imageoff", "messageon", "msgpushon", "messageoff", "msgpushoff", "push", "pushon", "pushto", "activationscenario", "deactivationscenario", "active", "xmlid"));
		if ( isset($_POST["push"]) )
			$this->push = 1;
		else
			$this->push = 0;
		return array($status, $message);
	}

	public function save($list_data = null)
	{
  		$new=false;
     	if ( ! isset($this->numero) || $this->numero == "" )
			$new = true;
 
      if ( preg_match("!^.*/(.*)$!", $this->imageon, $regs) )
			$this->imageon = $regs[1];
		  if ( preg_match("!^.*/(.*)$!", $this->imageoff, $regs) )
			$this->imageoff = $regs[1];

	    return parent::save(array("label", "type", "imageon", "imageoff", "messageon", "msgpushon", "messageoff", "msgpushoff", "push", "pushon", "pushto", "activationscenario", "deactivationscenario", "active", "carteid", "xmlid"));
	
  		If ( $new )
		{
			fn_InitAuthAllUser(get_class($this), $this->numero);
			$this->valeur = 0;
			$this->mysql_save();
		}

  }
	
	public function update()
	{
		if ( ! isset($this->pushon) )
			$this->pushon = "all";
		if ( ! in_array($this->pushon, array("all", "on", "off" ) ) )
			$this->pushon = "all";
		if ( $this->messageon == 'on')
 			$this->messageon = '%rasp433% - %etat%';
			$this->msgpushon = '%rasp433% - %etat%';

		if ( $this->messageoff == 'off' )
			$this->messageoff = '%rasp433% - %etat%';
			$this->msgpushoff = '%rasp433% - %etat%';

		if ( $this->message == '' )
			$this->message = "%rasp433% - %etat%";
		if ( ! isset($this->xmlid) )
		{
			$this->xmlid = 'led'.$this->no;
		}
		parent::update();
	}
	

	public function disp()
	{ 
 		$retour = "";
		if ( $this->active != 0 )
		{
			$retour  = '<div class="animated flipInY ' . $GLOBALS["config"]->csscontainer .'">';
			$retour .= '<div class="Entete '.get_class($this).'_label_'.$this->numero.'" id="'.get_class($this).'_label_'.$this->numero.'">'.$this->label.'</div>';
			$retour .= '<img class="CdeOn Pointer '.get_class($this).'_cdeon_'.$this->numero.'" id="'.get_class($this).'_cdeon_'.$this->numero.'"';
				if ( $_SESSION['Privilege'] >= (int)$GLOBALS["config"]->access->modrelai )
				{
						$retour .= ' onclick="AjaxAction(\''.get_class($this).'\', \''.$this->numero.'\', \'on\');"';
				}                                                                
			 $retour .= ' src="'.$this->imageon;
      $retour.='"/>';
			$retour .= '<img class="CdeOff Pointer '.get_class($this).'_cdeoff_'.$this->numero.'" id="'.get_class($this).'_cdeoff_'.$this->numero.'"';
				if ( $_SESSION['Privilege'] >= (int)$GLOBALS["config"]->access->modrelai )
				{
						$retour .= ' onclick="AjaxAction(\''.get_class($this).'\', \''.$this->numero.'\', \'off\');"';

				}                                                                
			 $retour .= ' src="'.$this->imageoff;
      $retour.='"/>';
      //$retour .= '<img class="Image433 '.get_class($this).'_image_'.$this->numero.'" id="'.get_class($this).'_image_'.$this->numero.'"';
      //$retour .= ' src="'.$this->imageoff;
      //$retour.='"/>';

		  $retour.=    '<div class="Caption '.get_class($this).'_caption_'.$this->numero.'"></div>';
      $retour .= '</div>'.PHP_EOL;
		}
		return $retour;
    
    
	}



	public function toggle()
	{
  /*
  if ($this->getstate()== 'on')
    {$value='off';}
  else
    {$value='on';}
  */
	if($this->all433=='O')
  {
		foreach($db['engines'] as $id=>$engine)
    {
    
      $commande='/var/www/radioEmission '.$GLOBALS["config"]->general->emetteur433->nowiringpi.' '.$GLOBALS["config"]->general->emetteur433->codetelecommande.' '.$this->numero.' '.$value;
			system($commande);
			system($commande);
		}
	}
  else
  { 
   $commande="/var/www/radioEmission ".$GLOBALS["config"]->general->emetteur433->nowiringpi." ".$GLOBALS["config"]->general->emetteur433->codetelecommande." ".$this->numero." ".$value;
   system($commande);
   system($commande);
	}
	$db[$this->numero]   = $value;
	$db["last_commande"] = $commande;

	$this->store($db);

	//fn_Trace("Carte ".$this->numero." : set relai ".$relai_id." ".$value, "carte", $this->timezone);
	}
  
	public function set_radio($value)
	{
  if($this->all433=='O')
  {
		foreach($db['engines'] as $id=>$engine)
    {
    
      $commande='/var/www/radioEmission '.$GLOBALS["config"]->general->emetteur433->nowiringpi.' '.$GLOBALS["config"]->general->emetteur433->codetelecommande.' '.$this->numero.' '.$value;
      system($commande);
			system($commande);
		}
	}
  else
  { $commande="/var/www/radioEmission ".$GLOBALS["config"]->general->emetteur433->nowiringpi." ".$GLOBALS["config"]->general->emetteur433->codetelecommande." ".$this->numero." ".$value;
   			//echo $commande;
      system($commande);
			system($commande);
 	}
	$db[$this->numero]   = $value;
  $db["last_commande"] = $commande;

	$this->store($db);

	//fn_Trace("Carte ".$this->numero." : set relai ".$relai_id." ".$value, "carte", $this->timezone);
	}
  
  public function store($datas)
  {
   	if(!file_exists("status.json")) touch("status.json");
   	file_put_contents("status.json",json_encode($datas));
  }
  
  public function unstore()
  {
   	if(!file_exists("status.json")) touch("status.json");
   	return json_decode(file_get_contents("status.json"),true);
  }

  public function getstate()
  {
	$db = (file_exists("status.json")?$this->unstore():array());
  return $db[$this->numero];
  }
	public function disp_list() {
		$checked="";
		if ($this->active=="on") $checked="checked";
		
		$xpath  = '//cartes/carte[@numero="'.$this->carteid.'"]';
		$labelcarteid = fn_GetByXpath($xpath, 'bal', 'label');
		$xpath  = '//type'.__class__.'s/type'.__class__.'[@numero="'.$this->type.'"]';
		$labeltype = fn_GetByXpath($xpath, 'bal', 'label');
		$return  = '<a href="?app=Mn&amp;page=Add&amp;class=' . __class__ . '&amp;numero=' . $this->numero . '&amp;del=true" class="'. $GLOBALS["classDispList"] .'">';
		$return .= '	<div class="well profile_view">';
		$return .= '		<div class="col-sm-12">';
		$return .= '			<h2 class="col-xs-10 col-sm-7 col-md-9 col-lg-9 left brief searchable filterable" data-beginletter="'.strtoupper(substr($this->label,0,1)).'"><span class="badge bg-red">'.$this->numero.'</span>&nbsp;'.$this->label.'</h2>';
		$return .= '			<input type="checkbox" data-toggle="toggle" data-size="small" disabled  data-style="pull-right" '.$checked.' >';
		$return .= '			<div class="left col-xs-8">';
		$return .= '				<p class="searchable">'.fn_GetTranslation("carte").' : '.$labelcarteid.' ('.$this->carteid.')</p>';
		$return .= '				<p class="searchable">'.fn_GetTranslation("type").' : '.$labeltype.' ('.$this->type.')</p>';		
		$return .= '				<p class="searchable">'.fn_GetTranslation("send_push").' : '.$this->push.'</p>';		
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