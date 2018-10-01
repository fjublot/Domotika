<?php
/*----------------------------------------------------------------*
* Titre : relai.php                                               *
* Classe de relai                                                 *
*----------------------------------------------------------------*/
require_once($GLOBALS["mvc_path"]."top.php");
class relai extends top
{
	public $creation, $type, $ta, $tb, $fullcss, $modelcss, $csson, $cssoff, $imageon, $imageoff, $messageon, $msgpushon, $messageoff, $msgpushoff, $message, $push, $pushon, $ctrlcarte, $ctrlbtn, $ctrlmessageup, $ctrlmessagedn, $carteid, $no, $activationscenario, $deactivationscenario, $active, $update_time, $xmlid;
	public $pushto = array();
	public function __construct($numero = "", $info = null)
	{
		parent::__construct($numero, $info);
		if ( ! isset($this->type) )
		{   $this->creation = true;
			$this->type = "I";
			$this->fullcss = "on";
			$this->modelcss = "whiteflat";
			$this->csson = "on";
			$this->cssoff = "off";
			$this->imageon = "on_v.png";
			$this->imageoff = "off.png";
			$this->messageon = "%label% - %etat%";
			$this->messageoff = "%label% - %etat%";
			$this->msgpushon = "%carte% - %label% %etat%";
			$this->msgpushoff = "%carte% - %label% %etat%";
			$this->message = "%label% - %etat%";
			$this->push = "off";
			$this->ctrlcarte = "#N/A";
			$this->ctrlbtn = "#N/A";
			$this->ctrlmessageup = "#N/A";
			$this->ctrlmessagedn = "#N/A";
			$this->ctrlmessage = "#N/A";
			$this->ta = "0";
			$this->tb = "0";
			$this->label = 'Relais';
			$this->active = "on";
			$this->pushon = "all";
		}
		else {
			$this->creation=false;
		}
		if ( file_exists($GLOBALS["root_path"]."/config/images/".$this->imageon) )
			$this->imageon = "config/images/".$this->imageon;
		else
			$this->imageon = 'images/relais/'.$this->imageon;
		if ( file_exists($GLOBALS["root_path"]."/config/images/".$this->imageoff) )
			$this->imageoff = "config/images/".$this->imageoff;
		else
			$this->imageoff = 'images/relais/'.$this->imageoff;
		return $this;
	}
	public function del()
	{
		fn_DelAuth(get_class($this), $this->numero);
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
		$return .= fn_HtmlHiddenField('action','action');
		$return .= fn_HtmlInputField('label', $this->label, 'text', 'relai_name', 'relai.label', '');
		$return .= fn_HtmlBinarySelectField('fullcss', $this->fullcss, 'fullcss', 'relai.fullcss',true);
		$return .= fn_HtmlStartFieldset(ucfirst(fn_GetTranslation('referer')), 'referer', true);
		$return .= fn_HtmlBinarySelectField('active', $this->active, 'active', 'relai.active',true);
		$return .= fn_HtmlSelectField('carteid', 'carte', 'relai.carteid');
		$return .= fn_HtmlSelectField('no', 'relai_no', 'relai.no');
		$return .= fn_HtmlSelectField('type', 'type', 'relai.type');
		$return .= fn_HtmlInputField('xmlid', $this->xmlid, 'text', 'nom_ipx', 'relai.labelipx', '', false, false, false, true);
		$return .= fn_HtmlInputField('ta', $this->ta, 'text', 'ta', 'relai.ta', '');
		$return .= fn_HtmlInputField('tb', $this->tb, 'text', 'tb', 'relai.tb', '');
		$return .= '	<div class="form-group">';
		$return .= '		<label class="control-label control-label col-md-3 col-sm-3 col-xs-12" for="BT_ConfigPushOnCard">';
		$return .= '			<span class="span-label">&nbsp</span>';
		$return .= '		</label>';
		$return .= 			fn_Help('relai.configrelai');
		$return .= '		<div id="input-btns" class="col-md-6 col-sm-6 col-xs-12">';
		$return .= '			<button id="BT_ConfigPushOnCard" class="btn btn-primary col-xs-12" onclick="jQuery(\'#action\').val(this.id);" name="BT_ConfigPushOnCard" type="">'.fn_GetTranslation("config_carte", __class__, $this->no, $this->carteid).'</button>';
		$return .= '		</div>';
		$return .= '	</div>';
		$return .= fn_HtmlEndFieldset();
		$return .= fn_HtmlStartFieldset(ucfirst(fn_GetTranslation('notification')), 'notification', true);
		$return .= fn_HtmlBinarySelectField('push', $this->push, 'send_push', 'relai.push');
		$return .= fn_HtmlSelectField('pushon', 'push_on', 'relai.pushon','',false);
		$return .= fn_HtmlSelectField('pushto', 'push_account', 'relai.pushto[]','',true);
		$return .= fn_HtmlInputField('msgpushon', $this->msgpushon, 'text', 'msgpush_on', 'relai.msgpushon', '');
		$return .= fn_HtmlInputField('msgpushoff', $this->msgpushoff, 'text', 'msgpush_off', 'relai.msgpushoff', '');
		$return .= fn_HtmlEndFieldset();
		$return .= fn_HtmlStartFieldset(ucfirst(fn_GetTranslation(__class__).' '.fn_GetTranslation('on')), __class__ .'on', true);
		$return .= fn_HtmlInputField('messageon', $this->messageon, 'text', 'message_on', 'relai.messageon', '');
		$return .= fn_HtmlButtonPicto('imageon', $this->imageon, 'image_on', 'relai.imageon');
		$return .= fn_HtmlBinarySelectField('csson', $this->csson, 'csson', 'relai.csson',true);
		$return .= fn_HtmlEndFieldset();
		$return .= fn_HtmlStartFieldset(ucfirst(fn_GetTranslation(__class__).' '.fn_GetTranslation('off')), __class__ . 'off', true);
		$return .= fn_HtmlInputField('messageoff', $this->messageoff, 'text', 'message_off', 'relai.messageoff', '');
		$return .= fn_HtmlInputField('message', $this->message, 'text', 'message', 'relai.message', '');
		$return .= fn_HtmlButtonPicto('imageoff', $this->imageoff, 'image_off', 'relai.imageoff');
		$return .= fn_HtmlBinarySelectField('cssoff', $this->cssoff, 'cssoff', 'relai.cssoff',true);
		$return .= fn_HtmlEndFieldset();
		$return .= fn_HtmlStartFieldset(ucfirst(fn_GetTranslation('controle')), 'controle', true);
		$return .= fn_HtmlSelectField('ctrlbtn', 'ctrl_btn', 'relai.ctrlcbtn',"",false,false);
		$return .= fn_HtmlInputField('ctrlmessageup', $this->ctrlmessageup, 'text', 'ctrl_message_up', 'relai.ctrlmessageup', '');
		$return .= fn_HtmlInputField('ctrlmessagedn', $this->ctrlmessagedn, 'text', 'ctrl_message_dn', 'relai.ctrlmessagedn', '');
		$return .= fn_HtmlEndFieldset();
		$return .= fn_HtmlStartFieldset(ucfirst(fn_GetTranslation('scenarii')), 'scenarii', true);
		$return .= fn_HtmlSelectField('activationscenario', 'activation_scenario', 'relai.activationscenario',"",false,true);
		$return .= fn_HtmlSelectField('desactivationscenario', 'desactivation_scenario', 'relai.desactivationscenario',"",false,true);
		$return .= fn_HtmlEndFieldset();
		$return .= '<script src="ressources/previewimage/jquery.previewimage.js"></script>';
		return $return;

	}
  public function js() {
	/*
	if ($this->creation == true) {
		$return  = 'jQuery("#carteid").prop("disabled", false);';
        $return .= 'jQuery("#no").prop("disabled", false);';
	}
	else {
		$return  = 'jQuery("#carteid").prop("disabled", true);';
		$return .= 'jQuery("#no").prop("disabled", true);';
	}
	*/
	$param="";
	global $filtre_carteid;
	if ($filtre_carteid!="") {
		$param = "&filtre_numero=".$filtre_carteid;
		if ($this->carteid == "")
			$this->carteid = $filtre_carteid;
	}
	
	$return = '
	        AjaxLoadSelectJson("carteid", "class=carte'.$param.'", false, "'.$this->carteid.'" );
	        AjaxLoadSelectJson("no", "carteid='.$this->carteid.'&class='.__class__.'", false, "'.$this->no.'" );
            AjaxLoadSelectJson("pushon", "class=pushon_type", false, "'.$this->pushon.'" );
	        AjaxLoadSelectJson("pushto", "class=pushto", true, "'.implode(',',$this->pushto).'" );
            AjaxLoadSelectJson("type", "class=type'.get_class($this).'", false, "'.$this->type.'" );
	        AjaxLoadSelectJson("ctrlbtn", "class=btn", true, "'.$this->ctrlbtn.'" );
	        AjaxLoadSelectJson("desactivationscenario", "class=scenario", true, "'.$this->deactivationscenario.'" );
	        AjaxLoadSelectJson("activationscenario", "class=scenario", true, "'.$this->activationscenario.'");
			jQuery("select#carteid").change(function(){
				var carteid = jQuery("select#carteid option:selected").attr(\'value\');
				var no = jQuery("select#no option:selected").attr(\'value\');
				if (typeof(carteid)!="undefined" && typeof(no)!="undefined") 
				    jQuery("#BT_Envoyer").prop("disabled", false);
				AjaxLoadSelectJson("no", "carteid="+carteid+"&class='.__class__.'", false, "'.$this->no.'" );
			});
			jQuery("select#no").change(function(){
				var carteid = jQuery("select#carteid option:selected").attr(\'value\');
				var no = jQuery("select#no option:selected").attr(\'value\');
				$("#xmlid").val("led"+$("#no").val());
				if (typeof(carteid)!="undefined" && typeof(no)!="undefined") 
					jQuery("#BT_Envoyer").prop("disabled", false);
			});';
			/*jQuery("select#ctrlcarte").change(function(){
				var ctrlcarte = jQuery("select#ctrlcarte option:selected").attr(\'value\');
				if (ctrlcarte!="undefined")
					AjaxLoadSelectJson("ctrlbtn", "carteid="+ctrlcarte+"&class=btn", false, "'.$this->ctrlbtn.'" );
				else
					jQuery("ctrlbtn").empty();
			});
			*/
	$return .= '
			jQuery(\'.previewimage\').preimage();';
	$return .='/* Règles de validation */
			var configvalidate = {
				// ordinary validation config
				form : \'#ajaxform\',
				// reference to elements and the validation rules that should be applied
				validate : {
					\'#label\' : {
					    validation : \'length\',
						length : \'5-20\'
					},
					\'#carteid\' : {
					validation : \'required\'
					},
					\'#no\' : {
					validation : \'required\'
					}
				}
			};
	';
		
		// Pour afficher la modal sur le click du bouton
		$return .='jQuery("#BT_modal_imageon").click(function () {jQuery("#modal_imageon").modal("show");});';
		// Pour charger le contenu de la modal lors de son chargement	
		$return .='jQuery("#modal_imageon").on("show.bs.modal", function () {AjaxLoadThumbnails("#modalbody_imageon", "dossier=' . get_class($this) . 's");});';
		// Pour afficher la modal sur le click du bouton
		$return .='jQuery("#BT_modal_imageoff").click(function () {jQuery("#modal_imageoff").modal("show");});';
		// Pour charger le contenu de la modal lors de son chargement	
		$return .='jQuery("#modal_imageoff").on("show.bs.modal", function () {AjaxLoadThumbnails("#modalbody_imageoff", "dossier=' . get_class($this) . 's");});';

		
		return $return;
	}
	
	public function receive_form($list_data = null)
	{
		list($status, $message) = parent::receive_form(array("label", "type", "ta", "tb", "fullcss", "modelcss", "csson", "cssoff", "imageon", "imageoff", "messageon", "messageoff", "msgpushon", "msgpushoff", "message", "push", "pushto", "pushon", "ctrlcarte", "ctrlbtn", "ctrlmessageup", "ctrlmessagedn", "carteid", "no", "activationscenario", "deactivationscenario", "active", "xmlid"));
		if ( preg_match("!^.*/(.*)$!", $this->imageon, $regs) )
			$this->imageon = $regs[1];
		if ( preg_match("!^.*/(.*)$!", $this->imageoff, $regs) )
			$this->imageoff = $regs[1];
		$this->creation=false;
		return array($status, $message);
	}
	public function save($list_data = null)
	{
		if ( preg_match("!^.*/(.*)$!", $this->imageon, $regs) )
			$this->imageon = $regs[1];
		if ( preg_match("!^.*/(.*)$!", $this->imageoff, $regs) )
			$this->imageoff = $regs[1];
		if ( ! isset($GLOBALS["auto"]) )
		{
			$model = fn_GetModel("carte", $this->carteid);
			$carte = new $model($this->carteid);
			$carte->set_relai_tab($this->no, $this->ta, $this->tb, $this->label);
		}
		$this->xmlid = 'led'.$this->no;
		return parent::save(array("label", "type", "ta", "tb", "fullcss", "modelcss", "csson", "cssoff", "imageon", "imageoff", "messageon", "messageoff", "msgpushon", "msgpushoff", "message", "push", "pushto", "pushon", "ctrlcarte", "ctrlbtn", "ctrlmessageup", "ctrlmessagedn", "carteid", "no", "activationscenario", "deactivationscenario", "active", "xmlid"));
	}
	public function update()
	{
		if ( ! isset($this->activationscenario) )
			$this->activationscenario = "";
		if ( ! isset($this->deactivationscenario) )
			$this->deactivationscenario = "";
		if ( ! isset($this->xmlid) && preg_match("/^carte-([0-9]*)-".get_class($this)."-([0-9]*)$/", $this->numero, $regs))
		{
			$this->xmlid = 'Relay'.$regs[2];
		}
		if ( ! isset($this->carteid) && preg_match("/^carte-([0-9]*)-".get_class($this)."-([0-9]*)$/", $this->numero, $regs) )
		{
			$this->carteid = $regs[1];
		}
		if ( preg_match("!^.*/(.*)$!", $this->imageon, $regs) )
			$this->imageon = $regs[1];
		if ( preg_match("!^.*/(.*)$!", $this->imageoff, $regs) )
			$this->imageoff = $regs[1];
	    	if ( ! isset($this->ta) )
			$this->ta = "0";
		if ( ! isset($this->tb) )
			$this->tb = "0";
		if ( ! isset($this->active) )
			$this->active = "on";
		if ( ! isset($this->pushon) )
			$this->pushon = "all";
		if ( ! in_array($this->pushon, array("all", "on", "off" ) ) )
			$this->pushon = "all";
		if ( $this->messageon == 'on')
 			$this->messageon = '%carte% - %label% - %etat%';
			$this->msgpushon = '%carte% - %label% - %etat%';
		if ( $this->messageoff == 'off' )
			$this->messageoff = '%carte% - %label% - %etat%';
			$this->msgpushoff = '%carte% - %label% - %etat%';
		if ( $this->message == '' )
			$this->message = "%carte% - %label% - %etat%";
		if ( ! isset($this->xmlid) )
		{
			$this->xmlid = 'led'.$this->no;
		}
		parent::update();
	}
	public function disp($commandactive=true, $jsremove=false)
	{
		$retour = "";
		$classcss='noteditable';
		if ($jsremove)
			$classcss='';		
		if ( $this->active == "on" ) {
			if ( $this->valeur == 0 ) {
				$image = $this->imageoff;
			}
			else {
				$image = $this->imageon;
			}
			$retour  = '<div class="Container animated flipInY ' . $GLOBALS["config"]->csscontainer .' ' .  $classcss.'" data-id="'.$this->config_class.'_'.$this->numero.'">';
			$retour .= '<div class="Entete '.get_class($this).'_label_'.$this->numero.'" >'.$this->label.'</div>';
			$dataattr = ' data-class="'.get_class($this).'" data-numero="'.$this->numero.'" data-ctrlbtn="' . $this->ctrlbtn . '" data-command="" ';
			if ($commandactive)
				$addclass='ajaxapiaction';
			else
				$addclass='';

			if ($this->fullcss=="on") {
				$retour .= '<div class="' . $addclass . ' ' . get_class($this) . '_css_' . $this->numero .' relaicss demo1" ' . $dataattr . '><input type="checkbox"><label></label></div>';
			}
			else {
				$retour .= '<img class="' . $addclass . ' Image Pointer ' . get_class($this) . '_image_' . $this->numero . '" alt="relai" src="'.$image.'"'.$dataattr.'/>';
			}
			$retour .= '<div class="Caption"></div>';
			$retour .= '<i class="js-remove fa fa-trash-o fa-2x"></i>';	
			$retour .= '</div>';
		}
		return $retour;
	}
	public function getxmlvalue($xmlStatus)
	{
		return parent::getxmlvalue($xmlStatus, 'led');
	}
	public function asxml($detail = false)
	{
		$return = "";
		if ( $this->active = "on" )
		{
			if ( $this->carteid == "" )
				$this->mysql_load();
			$return = "<".get_class($this)." numero='".$this->numero."'>";
			$return .= "<value>".$this->valeur."</value>" ;
			switch ($this->type) {
				case 'F':
					$infobulle =fn_GetTranslation('pulse', get_class($this)."s", $this->no, $this->carteid);
					$nextcommand = 'Switch';
					break;
				case 'ALLON':
					$infobulle =fn_GetTranslation('turn_all_on', get_class($this)."s", $this->carteid);
					$nextcommand = 'SetAllOn';
					break;
				case 'ALLOFF':
					$infobulle = fn_GetTranslation('turn_all_off', get_class($this)."s", $this->carteid);
					$nextcommand = 'SetAllOff';
					break;
				case 'I':
					if ( $this->valeur == 0 ) {
						$return .= "<image>".$this->imageoff."</image>" ;
						$infobulle =fn_GetTranslation('turn_on', get_class($this)."s", $this->no, $this->carteid);
						$nextcommand = 'SetOn';
					}
					if ( $this->valeur == 1 ) {
						$return .= "<image>".$this->imageon."</image>" ;
						$infobulle =fn_GetTranslation('turn_off', get_class($this)."s", $this->no, $this->carteid);
						$nextcommand = 'SetOff';
					}
					break;
				default:
					$infobulle = "";
					$nextaction = '';
			}	
			$return .= '<infobulle>'.$infobulle.'</infobulle>';
			$return .= '<nextcommand>'.$nextcommand.'</nextcommand>';
			if ( $detail == "true" ) {
				$return .= "<label>".$this->label."</label>";
			}
			$return .= "</".get_class($this).">";		
		}
		return $return;
	}

	public function asstring()
	{
		$Msg = "";
		if ( $this->active == "on" )
		{
		if ($this->valeur == 1) {
			$Msg=$this->messageon;
			$Msg = str_replace("%etat%", fn_GetTranslation('etat_down'), $Msg);
		}
		else {
			$Msg=$this->messageoff;
			$Msg = str_replace("%etat%", fn_GetTranslation('etat_up'), $Msg);
		}
		
		$Msg = str_replace("%label%", $this->label, $Msg);
		}
		if ($Msg!='')
			return $Msg.PHP_EOL;
		else
			return '';
	}
	
	public function configpushoncard (){
		// Instanciation de la carte associées
		$model = fn_GetModel("carte", $this->carteid);
		$carte = new $model($this->carteid);
		$errorcode = 0;
		
		// config Sortie
		$urlbase = $carte->geturl().'protect/settings/output1.htm';
		$urlbase .= '?output='.($this->no+1);
		$url  = '&relayname='.urlencode($this->label);
		$url  .= '&delayon='.$this->ta;
		$url  .= '&delayoff='.$this->tb;
		list($statusHttp, $responseHttp) = fn_GetContent($urlbase.$url);
		if ( $responseHttp === false ) {
			$status = fn_GetTranslation('update_status_ko');
			$errorcode++;
		}
		else
			$status = fn_GetTranslation('update_status_ok');
		$message = fn_GetTranslation('update_push', "sortie", __class__, $this->no, $status).'<br/>';
		fn_Trace("Carte ".$carte->numero." : config push - ".$urlbase.$url, "config_carte", $carte->timezone);
		
		// config du push (server)
		$urlbase = $carte->geturl().'protect/settings/push2.htm';
		$urlbase .= '?channel='.($this->no+32);
		$url  = '&server='.$_SERVER["HTTP_HOST"];
		$url .= '&port='.$_SERVER["SERVER_PORT"];
		//$url .= '&pass='.$carte->user."%3A".$carte->pass;
		$url .= '&enph=1';
		list($statusHttp, $responseHttp) = fn_GetContent($urlbase.$url);
		if ( $responseHttp === false ) {
			$status = fn_GetTranslation('update_status_ko');
			$errorcode++;
		}
		else
			$status = fn_GetTranslation('update_status_ok');
		$message .= fn_GetTranslation('update_push', "server", __class__, $this->no, $status).'<br/>';
		fn_Trace("Carte ".$carte->numero." : config push - ".$urlbase.$url, "config_carte", $carte->timezone);
	
		// config du push on
		$url  = '&cmd1='.urlencode(dirname($_SERVER["REQUEST_URI"]).'/?app=Ws&carteid='.$this->carteid.'&message='.$this->xmlid.'%20On');
		list($statusHttp, $responseHttp) = fn_GetContent($urlbase.$url);
		if ( $responseHttp === false ) {
			$status = fn_GetTranslation('update_status_ko');
			$errorcode++;
		}
		else
			$status = fn_GetTranslation('update_status_ok');
		$message .= fn_GetTranslation('update_push', "On", __class__, $this->no, $status).'<br/>';
		fn_Trace("Carte ".$carte->numero." : config push - ".$urlbase.$url, "config_carte", $carte->timezone);

		// config du push open
		$url  = '&cmd2='.urlencode(dirname($_SERVER["REQUEST_URI"]).'/?app=Ws&carteid='.$this->carteid.'&message='.$this->xmlid.'%20Off');
		list($statusHttp, $responseHttp) = fn_GetContent($urlbase.$url);
		if ( $responseHttp === false ) {
			$status = fn_GetTranslation('update_status_ko');
			$errorcode++;
		}
		else
			$status = fn_GetTranslation('update_status_ok');
		$message .= fn_GetTranslation('update_push', "Off", __class__, $this->no, $status).'<br/>';
		fn_Trace("Carte ".$carte->numero." : config push - ".$urlbase.$url, "config_carte", $carte->timezone);
	
		return array($errorcode, $message);
	}
  
  

	// asjson
	// Renvoi un tableau du status du cnt pouvant être encodé en json 
	public function asjson($detail = false) {
		if ( $this->active = "on" ) {
			if ( $this->carteid == "" )
				$this->mysql_load();
			if ( $this->valeur == 0 ) { // Si le relai est ouvert
				$image = $this->imageoff;
				if ($this->type=='I') 
					$infobulle =fn_GetTranslation('turn_on', get_class($this)."s", $this->no, $this->carteid);
				elseif ($this->type=='F') 
					$infobulle =fn_GetTranslation('pulse', get_class($this)."s", $this->no, $this->carteid);
				elseif ($this->type=='ALLON') 
					$infobulle =fn_GetTranslation('turn_all_on', get_class($this)."s", $this->carteid);
				elseif ($this->type=='ALLOFF') 
					$infobulle =fn_GetTranslation('turn_all_off', get_class($this)."s", $this->carteid);
			}
			else { // Si le relai est fermé
				$image = $this->imageon;
				if ($this->type == 'I') 
					$infobulle =fn_GetTranslation('turn_off', get_class($this)."s", $this->no, $this->carteid);
				elseif ($this->type=='F')
					$infobulle =fn_GetTranslation('pulse', get_class($this)."s", $this->no, $this->carteid);
				elseif ($this->type=='ALLON')
					$infobulle = fn_GetTranslation('turn_all_on', get_class($this)."s", $this->carteid);
				elseif ($this->type=='ALLOFF')
					$infobulle =fn_GetTranslation('turn_all_off', get_class($this)."s", $this->carteid);
			}
			
			if ( $detail )
				$relaijson = array('numero' => $this->numero, 'label' => $this->label, 'type' => $this->type, 'value' => $this->valeur, 'infobulle' => $infobulle, 'image' => $image);
			else
				$relaijson = array('numero' => $this->numero, 'value' => $this->valeur, 'infobulle' => $infobulle, 'image' => $image);
		}
		return $relaijson;
	}
	
	public function backpush($etat, $update_time)
	{
		$numero_scenario = 0;
		switch ($etat)
		{
			Case 'On':
				if ( $this->msgpushon != '' )
				{
					$Msg = $this->msgpushon;
				}
				$this->valeur = 1;
				$numero_scenario = $this->activationscenario;
				break;
			Case 'Off':
				if ( $this->type == 'F' )
				{
					$Msg = '';
				}
				else
				{
					if ( $this->msgpushoff != '')
					{
						$Msg = $this->msgpushoff;
					}
 				}
				$this->valeur = 0;
				$numero_scenario = $this->deactivationscenario;
				break;
			Default:
				$Msg = "Etat %label% incomprehensible ".$etat;
				fn_Trace("Etat relai incomprehensible ".$etat, "push");
				break;
		}
		$Msg = str_replace("%label%", $this->label, $Msg);
		if ( $this->type == 'F' )
			$Msg = str_replace("%etat%", fn_GetTranslation('etat_impulsion')." %etat%", $Msg);
		if ( $this->valeur == 1 )
			$Msg = str_replace("%etat%", fn_GetTranslation('etat_down'), $Msg);
		else
			$Msg = str_replace("%etat%", fn_GetTranslation('etat_up'), $Msg);
		$this->update_time = $update_time;
		$this->mysql_save();
		$model = fn_GetModel("carte", $this->carteid);
		$carte = new $model($this->carteid);
  		$Msg = str_replace("%carte%", $carte->label, $Msg);
		$send = $this->pushon == "all" or ( $this->pushon == "on" and $this->valeur == 1 ) or ( $this->pushon == "off" and $this->valeur == 0 );
		if ( $this->push == "on" && $Msg != "" && $send )
		{
			//fn_Trace("Carte ".$carte->label." Relai ".$this->label." : pushto ".$this->pushto.'->'.$Msg, "push");
      		foreach($this->pushto as $compte_pushto)
		{
			if ( $this->push == "on" && $Msg != "" && $send )
			{
				if ( isset($carte) )
					fn_Trace("Carte ".$carte->label." Relai ".$this->label." : pushto ".$compte_pushto.'->'.$Msg, "push");
				else
					fn_Trace("Relai ".$this->label." : pushto ".$compte_pushto.'->'.$Msg, "push");
			fn_PushTo($Msg, $compte_pushto);
			}
		}
    }  
		if ( $numero_scenario != 0 )
		{
			$scenario = new scenario($numero_scenario);
			$scenario->run();
		}
		return $Msg;
	}
	public function moveUp()
	{
	    if ($this->previousSibling)
	    {
	        $this->parentNode->insertBefore($this, $this->previousSibling);
	    }
	}
	public function moveDown()
	{
	    if ($this->nextSibling)
	    {
	        $this->parentNode->insertBefore($this->nextSibling, $this);
	    }
	}

	public function disp_list() {
		$checked="";
		if ($this->active=="on") $checked="checked";
		$xpath  = '//cartes/carte[@numero="'.$this->carteid.'"]';
		$labelcarteid = fn_GetByXpath($xpath, 'bal', 'label');
		$xpath  = '//type'.__class__.'s/type'.__class__.'[@numero="'.$this->type.'"]';
		$labeltype = fn_GetByXpath($xpath, 'bal', 'label');
		$return  = '<a href="?app=Mn&amp;page=Add&amp;class=' . __class__ . '&amp;numero=' . $this->numero . '&amp;del=true" class="'. $GLOBALS["classDispList"] .'">';
		//return .= '	<div class="ribbon blue"><span class="double">'.$labelcarteid.'<br>'.__class__.' ('.$this->no.')</span></div>';
		$return .= '	<div class="well profile_view">';
		$return .= '		<div class="col-sm-12">';
		$return .= '			<div>';
		$return .= '                <span class="col-xs-9 left brief searchable filterable" style="margin-top: 4px;" data-beginletter="'.strtoupper(substr($this->label,0,1)).'">';
		$return .= '					<span class="badge bg-red">'.$this->numero.'</span>';
		$return .= '					<span class="h2">'.$this->label.'</span>';
		$return .= '					</span>';
		$return .= '			        <input type="checkbox" data-toggle="toggle" data-size="small" disabled  data-style="pull-right" '.$checked.' >';
		$return .= '            </div>';
		$return .= '			<div class="left col-xs-12">';
		$return .= '				<p class="searchable">'.ucfirst(fn_GetTranslation("carte")).' : '.$labelcarteid.' ('.$this->carteid.')</p>';
		$return .= '				<p class="searchable">'.ucfirst(fn_GetTranslation("relai")).' : '.($this->no+1).' ('.$this->xmlid.')</p>';
		$return .= '				<p class="searchable">'.ucfirst(fn_GetTranslation("type")).' : '.$labeltype.' ('.$this->type.')</p>';		
		//$return .= '				<p class="searchable">'.ucfirst(fn_GetTranslation("send_push")).' : <input type="checkbox" data-toggle="toggle" data-size="small" disabled  data-style="pull-right" '.$checkedpush.' ></p>';		
		$return .= '			</div>';
/*
		$return .= '			<div class="right col-xs-4 text-center">';
		//$return .= '				<img src="'.$this->picto.'" alt="" class="img-circle img-responsive">';
		$return .= '			</div>';
*/
		$return .= '		</div>';
		$return .= '	</div>';
		$return .= '</a>';
		echo $return;
	}

	public function SetOn() {
		if ($this->type == "F")
			return $this->SetSwitch();
		else {
			$ErrorMsg = "";
			$StatusMsg = "";
			$model = fn_GetModel("carte", $this->carteid);
			$carte = new $model($this->carteid);
			list($urlRelai, $statusHttpRelai, $responseHttpRelai) = $carte->SetRelai($this->no, 1);
			
			if ($responseHttpRelai != False && $statusHttpRelai == "200") {
				if ( $this->messageon == "" )
					$StatusMsg = fn_GetTranslation('relai_on', $carte->label, $this->label);
				else
					$StatusMsg = $this->messageon;
			}
			else
				$ErrorMsg = htmlspecialchars(ucfirst(fn_GetTranslation('error_communication', $carte->label)));
			$carte->get_status();
			$this->getxmlvalue($carte->status);
			$StatusMsg = str_replace("%etat%", fn_GetTranslation('etat_down'), $StatusMsg);
			$StatusMsg = str_replace("%carte%", $carte->label, $StatusMsg);
			$StatusMsg = str_replace("%label%", $this->label, $StatusMsg);
			return array($this->valeur, $StatusMsg, $ErrorMsg, $urlRelai, $statusHttpRelai);
		}
	}

	public function SetOff() {	
		if ($this->type == "F")
			return $this->SetSwitch();
		else {	
			$ErrorMsg = "";
			$StatusMsg = "";
			$model = fn_GetModel("carte", $this->carteid);
			$carte = new $model($this->carteid);
			list($urlRelai, $statusHttpRelai, $responseHttpRelai) = $carte->SetRelai($this->no, 0);
			if ($responseHttpRelai != False && $statusHttpRelai == "200") {
				if ( $this->messageoff == "" )
					$StatusMsg = fn_GetTranslation('relai_off', $carte->label, $this->label);
				else
					$StatusMsg = $this->messageoff;
			}
			else
				$ErrorMsg = htmlspecialchars(ucfirst(fn_GetTranslation('error_communication', $carte->label)));
			$carte->get_status();
			$this->getxmlvalue($carte->status);
			$StatusMsg = str_replace("%etat%", fn_GetTranslation('etat_up')." %etat%", $StatusMsg);
			$StatusMsg = str_replace("%carte%", $carte->label, $StatusMsg);
			$StatusMsg = str_replace("%label%", $this->label, $StatusMsg);
			return array($this->valeur, $StatusMsg, $ErrorMsg, $urlRelai, $statusHttpRelai);
		}
	}
	
	public function SetSwitch()
	{	
		$ErrorMsg = "";
		$StatusMsg = "";
		$model = fn_GetModel("carte", $this->carteid);
		$carte = new $model($this->carteid);
		list($urlRelai, $statusHttpRelai, $responseHttpRelai) = $carte->FurtifRelai($this->no, 1);
		if ($responseHttpRelai != False && $statusHttpRelai == "200") {
			if ( $this->message == "" )
				$StatusMsg = fn_GetTranslation('relai_imp', $carte->label, $this->label);
			else
				$StatusMsg = $this->message;
		}
		else
			$ErrorMsg = htmlspecialchars(ucfirst(fn_GetTranslation('error_communication', $carte->label)));
		$carte->get_status();
		$this->getxmlvalue($carte->status);
		$StatusMsg = str_replace("%etat%", fn_GetTranslation('etat_impulsion'), $StatusMsg);
		$StatusMsg = str_replace("%carte%", $carte->label, $StatusMsg);
		$StatusMsg = str_replace("%label%", $this->label, $StatusMsg);
		return array($this->valeur, $StatusMsg, $ErrorMsg, $urlRelai, $statusHttpRelai);	
	}
	

	public function GetState()
	{	
		$model = fn_GetModel("carte", $this->carteid);
		$carte = new $model($this->carteid);
		$carte->get_status();
		$this->getxmlvalue($carte->status);
		return $this->valeur;
		
	}	
}
?>