<?php
/*----------------------------------------------------------------*
* Titre : btn.php                                                 *
* Classe de btn                                                   *
*----------------------------------------------------------------*/
class btn extends top
{
	public $label, $type, $mode, $virtualrelay, $linktorelay, $linktocnt, $messageup, $messagedn, $imageup, $imagedn, $push, $pushto, $pushon, $msgpushup, $msgpushdn, $carteid, $no, $pressscenario, $relachescenario, $active, $update_time, $xmlid, $inv;
	public $creation, $valeur;
	//public $pushto = array();
	public function __construct($numero = "", $info = null)
	{
		parent::__construct($numero, $info);
		$this->creation = false;
		if ( ! isset($this->type) )
		{   
			$this->creation = true;
			$this->type = "img";
			$this->imageup = "BulleGris.png";
			$this->imagedn = "BulleBleu.png";
			$this->push = "off";
			$this->messageup = "%etat%";
			$this->messagedn = "%etat%";
			$this->msgpushup = "%label% - %etat%";
			$this->msgpushdn = "%label% - %etat%";
			$this->carteid = "";
			$this->no = "";
			$this->label = 'Etat Bouton';
			$this->active = 'on';
			$this->pushon = "all";
			$this->virtualrelay = 'off';
			$this->typerelay = '';
		}
		if ( file_exists($GLOBALS["root_path"]."/config/images/".$this->imageup) )
			$this->imageup = "config/images/".$this->imageup;
		else
			$this->imageup = 'images/btns/'.$this->imageup;
		if ( file_exists($GLOBALS["root_path"]."/config/images/".$this->imagedn) )
			$this->imagedn = "config/images/".$this->imagedn;
		else
			$this->imagedn = 'images/btns/'.$this->imagedn;
		return $this;
	}
	public function del()
	{
		fn_DelAuth(get_class($this), $this->numero);
		return parent::del();
	}
	public function form($page=null)
	{
		$return  =  fn_HtmlStartPanel($this->label, fn_GetTranslation(__class__), __class__, $this->numero);
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
		$return .= fn_HtmlStartFieldset(ucfirst(fn_GetTranslation('display')), 'GrpDisplay');
		$return .= fn_HtmlInputField('label', $this->label, 'text', 'btn_label', 'btn.label', '');
		$return .= fn_HtmlSelectField('type', 'type', 'relai.type');
		$return .= fn_HtmlBinarySelectField('virtualrelay', $this->virtualrelay, 'virtualrelay', 'btn.virtualrelay', false);
		$return .= fn_HtmlEndFieldset();
		$return .= fn_HtmlStartFieldset(ucfirst(fn_GetTranslation('referer')), 'GrpReferer', true);
		$return .= fn_HtmlBinarySelectField('active', $this->active, 'active', 'btn.active', true);
		$return .= fn_HtmlSelectField('carteid', 'carte', 'btn.carteid');
		$return .= fn_HtmlSelectField('no', 'btn_no', 'btn.no');
		//$return .= fn_HtmlInputField('labelipx', $this->labelipx, 'text', 'nom_ipx', 'btn.labelipx', '');
		$return .= fn_HtmlInputField('xmlid', $this->xmlid, 'text', 'nom_ipx', 'btn.labelipx', '', false, false, false, true);
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
		$return .= fn_HtmlStartFieldset(ucfirst(fn_GetTranslation('notification')), 'GrpNotification', true);
		$return .= fn_HtmlBinarySelectField('push', $this->push, 'send_push', 'btn.push');
		$return .= fn_HtmlSelectField('pushon', 'push_on', 'btn.pushon','',false);
		$return .= fn_HtmlSelectField('pushto', 'push_account', 'btn.pushto','',true);
		$return .= fn_HtmlEndFieldset();
		$return .= fn_HtmlStartFieldset(ucfirst(fn_GetTranslation('btn_up')), 'GrpBtnUp', true);
		$return .= fn_HtmlInputField('messageup', $this->messageup, 'text', 'message_up', 'btn.messageup', '');
		$return .= fn_HtmlInputField('msgpushup', $this->msgpushup, 'text', 'msgpush_up', 'btn.msgpushup', '');
		//$return .= fn_HtmlSelectField('imageup', 'image_up', 'btn.imageup',"UpdateImage(this.id);",false,true);
		$return .= fn_HtmlButtonPicto('imageup', $this->imageup, 'image_up', 'btn.imageup');
 		$return .= fn_HtmlEndFieldset();
		$return .= fn_HtmlStartFieldset(ucfirst(fn_GetTranslation('btn_dn')), 'GrpBtnDn', true);
		$return .= fn_HtmlInputField('messagedn', $this->messagedn, 'text', 'message_dn', 'btn.messagedn', '');
		$return .= fn_HtmlInputField('msgpushdn', $this->msgpushdn, 'text', 'msgpush_dn', 'btn.msgpushdn', '');
		//$return .= fn_HtmlSelectField('imagedn', 'image_dn', 'btn.imagedn',"UpdateImage(this.id);",false,true);
		$return .= fn_HtmlButtonPicto('imagedn', $this->imagedn, 'image_dn', 'btn.imagedn');
 		$return .= fn_HtmlEndFieldset();
		$return .= fn_HtmlStartFieldset(ucfirst(fn_GetTranslation('scenarii')), 'GrpScenarii', true);
		$return .= fn_HtmlSelectField('pressscenario', 'press_scenario', 'btn.pressscenario');
		$return .= fn_HtmlSelectField('relachescenario', 'release_scenario', 'btn.relachescenario');
 		$return .= fn_HtmlEndFieldset();
		$return .= fn_HtmlStartFieldset(ucfirst(fn_GetTranslation('AssigndigitalInputtoOutput')), 'GrpAssign', true);
		$return .= fn_HtmlSelectField('mode', 'mode', 'btn.mode','',false);
		$return .= fn_HtmlBinarySelectField('inv', $this->inv, 'btn_inv', 'btn.inv');
		$return .= fn_HtmlSelectField('linktocnt', 'linktocnt', 'btn.linktocnt','',false);
		$return .= fn_HtmlSelectField('linktorelay', 'linktorelay', 'btn.linktorelay','',true);
		$return .= fn_HtmlEndFieldset();
		$return .= '<script src="ressources/previewimage/jquery.previewimage.js"></script>';
		return $return;
	}
	public function js()
	{	$return = "";
		if ($this->carteid!="" && $this->no!="")
			$return .= 'jQuery("#BT_Envoyer").prop("disabled", false);';
		$param="";
		
		global $filtre_carteid;
		if ($filtre_carteid!="") {
			$param = "&filtre_numero=".$filtre_carteid;
			if ($this->carteid == "")
				$this->carteid = $filtre_carteid;
		}
	
		$return = '
	        AjaxLoadSelectJson("carteid", "class=carte'.$param.'", false, "'.$this->carteid.'" );
			AjaxLoadSelectJson("no", "carteid='.$this->carteid.'&class='.get_class($this).'", false, "'.$this->no.'" );
            AjaxLoadSelectJson("pushon", "class=pushon_type", false, "'.$this->pushon.'" );
			AjaxLoadSelectJson("type", "class=type'.get_class($this).'", false, "'.$this->type.'" );
			AjaxLoadSelectJson("pushto", "class=pushto", true, "'.$this->pushto.'" );
			AjaxLoadSelectJson("relachescenario", "class=scenario", true, "'.$this->relachescenario.'" );
			AjaxLoadSelectJson("pressscenario", "class=scenario", true, "'.$this->pressscenario.'" );
			AjaxLoadSelectJson("mode", "class=btnmode", false, "'.$this->mode.'" );
			AjaxLoadSelectJson("linktorelay", "class=relai&carteid='.$this->carteid.'", false, "'.$this->linktorelay.'" );
			AjaxLoadSelectJson("linktocnt", "class=cnt&carteid='.$this->carteid.'", true, "'.$this->linktocnt.'" );

			jQuery("select#carteid").change(function(){
				var carteid = jQuery("select#carteid option:selected").attr(\'value\');
				var no = jQuery("select#no option:selected").attr(\'value\');
				if (typeof(carteid)!="undefined" && typeof(no)!="undefined") 
				    jQuery("#BT_Envoyer").prop("disabled", false);
				AjaxLoadSelectJson("no", "carteid="+carteid+"&class='.__class__.'", false, "'.$this->no.'" );
				AjaxLoadSelectJson("linktorelay", "carteid="+carteid+"&class=relai", false, "'.$this->linktorelay.'" );
				AjaxLoadSelectJson("linktocnt", "carteid="+carteid+"&class=cnt", true, "'.$this->linktocnt.'", true);
				
			});
			jQuery("select#no").change(function(){
				var carteid = jQuery("select#carteid option:selected").attr(\'value\');
				var no = jQuery("select#no option:selected").attr(\'value\');
				if (typeof(carteid)!="undefined" && typeof(no)!="undefined") {
					jQuery("#xmlid").val("btn"+(no));
					jQuery("#BT_Envoyer").prop("disabled", false);
				}
			});
			
			/* Règles de validation */
			var configvalidate = {
				// ordinary validation config
				form : \'#ajaxform\',
				// reference to elements and the validation rules that should be applied
				validate : {
					\'#label\' : {
					    validation : \'length\',
						length : \'1-20\'
					},
					\'#carteid\' : {
					validation : \'required\'
					},
					\'#no\' : {
					validation : \'required\'
					}
				}
			};			
	
			$(\'.previewimage\').preimage();';

		// Pour afficher la modal sur le click du bouton
		$return .='jQuery("#BT_modal_imageup").click(function () {jQuery("#modal_imageup").modal("show");});';
		// Pour charger le contenu de la modal lors de son chargement	
		$return .='jQuery("#modal_imageup").on("show.bs.modal", function () {AjaxLoadThumbnails("#modalbody_imageup", "dossier=' . get_class($this) . 's");});';
		// Pour afficher la modal sur le click du bouton
		$return .='jQuery("#BT_modal_imagedn").click(function () {jQuery("#modal_imagedn").modal("show");});';
		// Pour charger le contenu de la modal lors de son chargement	
		$return .='jQuery("#modal_imagedn").on("show.bs.modal", function () {AjaxLoadThumbnails("#modalbody_imagedn", "dossier=' . get_class($this) . 's");});';
			
		return $return;
	}
	public function receive_form($list_data = null)
	{
		list($status, $message) = parent::receive_form(array("label", "type", "virtualrelay", "mode", "linktorelay", "linktocnt", "imageup", "imagedn", "push", "pushto", "pushon", "carteid", "no", "msgpushup", "msgpushdn", "messageup", "messagedn", "pressscenario", "relachescenario", "active", "xmlid", "inv"));
		if ( preg_match("!^.*/(.*)$!", $this->imageup, $regs) )
			$this->imageup = $regs[1];
		if ( preg_match("!^.*/(.*)$!", $this->imagedn, $regs) )
			$this->imagedn = $regs[1];
		if ( $this->active == "")
			 $this->active = 'off';
		if ( $this->virtualrelay == "")
			 $this->virtualrelay = 'off';
		if ( $this->push == "")
			 $this->push = 'off';	
		if ( $this->inv == "")
			 $this->inv = 'off';
			 
		return array($status, $message);
	}
	
	public function save($list_data = null)
	{
		if ( preg_match("!^.*/(.*)$!", $this->imageup, $regs) )
			$this->imageup = $regs[1];
		if ( preg_match("!^.*/(.*)$!", $this->imagedn, $regs) )
			$this->imagedn = $regs[1];
		$this->xmlid = 'btn'.$this->no;

		$return = parent::save(array("label", "type", "virtualrelay", "mode", "linktorelay", "linktocnt", "imageup", "imagedn", "push", "pushto", "pushon", "carteid", "no", "msgpushup", "msgpushdn", "messageup", "messagedn", "pressscenario", "relachescenario", "active", "xmlid", "inv"));
		if ( $this->creation )
		{
			fn_InitAuthAllUser(get_class($this), $this->numero);
			$this->valeur = 0;
			$this->mysql_save();
		}
		return $return;
	}
	public function update()
	{
		if ( ! isset($this->pushto) )
			$this->pushto = "";
		if ( ! isset($this->relachescenario) )
			$this->relachescenario = "";
		if ( ! isset($this->pressscenario) )
			$this->pressscenario = "";
		if ( ! isset($this->xmlid) )
		{
			if ( preg_match("/^carte-([0-9]*)-".get_class($this)."-([0-9]*)$/", $this->numero, $regs) )
				$this->xmlid = 'btn'.$regs[2];
			else
				$this->xmlid = '';
		}
		if ( preg_match("!^.*/([^/]*)$!", $this->imageup, $regs) )
			$this->imageup = $regs[1];
		if ( preg_match("!^.*/([^/]*)$!", $this->imagedn, $regs) )
			$this->imagedn = $regs[1];
		if ( !isset($this->active) )
			$this->active = 1;
		if ( ! isset($this->pushon) )
			$this->pushon = "all";
		if ( ! in_array($this->pushon, array("all", "up", "down" ) ) )
			$this->pushon = "all";
		if ( $this->messageup == 'up' )
		{ $this->msgpushup = '%carte% - %label% - %etat%';
			if ( $this->carteid != "" )
				$this->messageup = '%etat%';
			else
				$this->messageup = '%label% - %etat%';
		}
		if ( $this->messagedn == 'dn')
		{
			$this->msgpushdn = '%carte% - %label% - %etat%';
	      if ( $this->carteid != "" )
				$this->messagedn = '%etat%';
			else
				$this->messagedn = '%label% - %etat%';
		}
		if ( ! isset($this->xmlid) )
		{
			$this->xmlid = 'btn'.$this->no;
		}
		parent::update();
	}
	public function verif_before_del()
	{
		return true;
	}
	public function disp($commandactive=true, $jsremove=false)
	{
		$return = "";
		$classcss='noteditable';
		if ($jsremove)
			$classcss='';		
		if ($commandactive && $this->virtualrelay == 'on')
			$classcss.=' Pointer ';
		
		if ( $this->active == 'on' ) {
			if ( $this->valeur == '0' ) {
				$image = $this->imagedn;
				$txt = $this->messagedn;
			}
			elseif ( $this->valeur == '1' ) {
				$image = $this->imageup;
				$txt = $this->messageup;
			}
			else {
				$image="images/btns/BulleGris.png";
				$txt="#N/A";
			}

			$return  = '<div class=" Container animated flipInY  ' . $GLOBALS["config"]->csscontainer .' ' .  $classcss.'" data-id="'.$this->config_class.'_'.$this->numero.'">';
			$return .= '<div class="Entete btn_label_'.$this->numero.'" >'.$this->label.'</div>';
			if ($this->virtualrelay == 'on') {
				if ($commandactive) {
					$dataattr = ' data-class="'.get_class($this).'" data-numero="'.$this->numero.'" data-ctrlbtn="" data-command=""';
					$addclass='ajaxapiaction';
				}
				else {
					$dataattr = '';
					$addclass='';
				}
				$return .= '<img class="' . $addclass . ' Image Pointer ' . get_class($this) . '_image_' . $this->numero . '" alt="relai" src="'.$image.'"'.$dataattr.'/>';
				$return .= '<div class="Caption btn_txt_'.$this->numero;
				if ($this->type=="img")
					$return.=' Visibility-hidden';
				$return.='" >'.$txt.'</div>';
			}
			else {
				$return .= '<img class="Image '.get_class($this).'_image_'.$this->numero;
				if ($this->type=="txt")
					$return.=' Visibility-hidden';
				$return.='" alt="btn" src="'.$image.'"/>';
				$return .= '<div class="Caption btn_txt_'.$this->numero;
				if ($this->type=="img")
					$return.=' Visibility-hidden';
				$return.='" >'.$txt.'</div>';
			} 
			$return.='<i class="js-remove fa fa-trash-o fa-2x"></i>';	
			$return .= '</div>';
		}
		return $return;
	}
	
	public function getxmlvalue($xmlStatus)
	{
		parent::getxmlvalue($xmlStatus, 'btn');
		if ( $this->valeur == "up" )
			$this->valeur = 0;
		elseif ( $this->valeur == "dn" )
			$this->valeur = 1;
		return $this->valeur;
	}
	public function asxml($detail = false) {
		$return = "";
		if ( $this->active == 'on' )
		{
			if ( $this->carteid == "" )
				$this->mysql_load();
			$return = "<".get_class($this)." numero='".$this->numero."'>";
			$txt = "";
			$image = "";
			if ( $this->valeur == 1 ) {
				$image = $this->imagedn;
				$txt   = $this->messagedn;
			}
			elseif ( $this->valeur == 0 ) {
				$image = $this->imageup;
				$txt   = $this->messageup;
			}
			if ( strlen($txt) == 0 )
			{
				$txt = "#N/A";
			}
			if ( strlen($image) == 0 )
			{
				$image = "#N/A";
			}
			if ( $this->valeur == 1 )
				$txt = str_replace("%etat%", fn_GetTranslation('etat_close'), $txt);
			else
			 	$txt = str_replace("%etat%", fn_GetTranslation('etat_open'), $txt);
     		$txt = str_replace("%label%", $this->label, $txt);
			$return .= "<image>".$image."</image>" ;
			$return .= "<txt>".($txt)."</txt>" ;
			$return .= "<valeur>".$this->valeur."</valeur>" ;
			if ($this->virtualrelay == 'on') {
				if ( $this->valeur == 0 ) {
					$infobulle =fn_GetTranslation('turn_on', get_class($this)."s", $this->no, $this->carteid);
					$nextcommand = 'SetOn';
				}
				else if ( $this->valeur == 1 ) {
					$infobulle =fn_GetTranslation('turn_off', get_class($this)."s", $this->no, $this->carteid);
					$nextcommand = 'SetOff';
				}
				else {
					$infobulle ='';
					$nextcommand = '';
				}
			$return .= '<infobulle>'.$infobulle.'</infobulle>';
			$return .= '<nextcommand>'.$nextcommand.'</nextcommand>';
			}	
			if ( $detail == "true"  ) {
				$return .= "<label>".$this->label."</label>";
			}
			$return .= "</".get_class($this).">";
		}
		return $return;
	}
	
	public function backpush($etat, $update_time)
	{
		$Msg = '';
		$numero_scenario = 0;
		switch ($etat)
		{
			Case 'Open':
				if ( $this->msgpushup != '' )
				    $Msg = $this->msgpushup;
				$this->valeur = 0;
				$numero_scenario = $this->relachescenario;
				break;
			Case 'Close':
				if ( $this->msgpushdn != '' )
	 				$Msg = $this->msgpushdn;
				$this->valeur = 1;
				$numero_scenario = $this->pressscenario;
				break;
			Default:
				$Msg = fn_GetTranslation('statena', $etat);
				fn_Trace(fn_GetTranslation('statena', $etat), "push");
				break;
		}
		if ( $this->valeur == 1 )
			$Msg = str_replace("%etat%", fn_GetTranslation('etat_close'), $Msg);
		else
			$Msg = str_replace("%etat%", fn_GetTranslation('etat_open'), $Msg);
		$Msg = str_replace("%label%", $this->label, $Msg);
		$Msg = str_replace("%messageup%", $this->messageup, $Msg);
		$Msg = str_replace("%messagedn%", $this->messagedn, $Msg);
		$this->update_time = $update_time;
		$this->mysql_save();
		if ( $this->carteid != "" )
		{
			$model = fn_GetModel("carte", $this->carteid);     
			$carte = new $model($this->carteid);
	  		$Msg = str_replace("%carte%", $carte->label, $Msg);
  		}
		$send = false;
		if (($this->pushon == "all") || ( $this->pushon == "up" and $this->valeur == 1 ) || ( $this->pushon == "down" and $this->valeur == 0 ))
			$send = true;
		$comptes_pushto=explode(',',$this->pushto);
		foreach($comptes_pushto as $compte_pushto) {
			if ( $this->push == "on" && $Msg != "" && $send )
			{
				if ( isset($carte) )
					fn_Trace("Carte ".$carte->label." Btn ".$this->label." : pushto ".$compte_pushto.'->'.$Msg, "push");
				else
					fn_Trace("Btn ".$this->label." : pushto ".$compte_pushto.'->'.$Msg, "push");
			fn_PushTo($Msg, $compte_pushto);
			}
		}
		if ( $numero_scenario != "#N/A" )
		{
			$scenario = new scenario($numero_scenario);
			$scenario->run();
			$Msg .= "(".$scenario->label.")";
		}
		return $Msg;
	}
	public function disp_list() {
		$checked="";
		if ($this->active=="on") $checked="checked";
		$xpath  = '//cartes/carte[@numero="'.$this->carteid.'"]';
		$labelcarteid = fn_GetByXpath($xpath, 'bal', 'label');
		$xpath  = '//type'.__class__.'s/type'.__class__.'[@numero="'.$this->type.'"]';
		$labeltype = fn_GetByXpath($xpath, 'bal', 'label');

		$return  = '<a href="?app=Mn&amp;page=Add&amp;class=' . __class__ . '&amp;numero=' . $this->numero . '&amp;del=true" class="'. $GLOBALS["classDispList"] .'">';
		if ($this->virtualrelay == 'on') {
			$return .= '	<div class="ribbon blue"><span>'.ucfirst(fn_GetTranslation("relai")).'</span></div>';
		}
		$return .= '	<div class="well profile_view">';
		$return .= '		<div class="col-xs-12">';
		$return .= '			<div>';
		$return .= '                <span class="col-xs-9 left brief searchable filterable" style="margin-top: 4px;" data-beginletter="'.strtoupper(substr($this->label,0,1)).'">';
		$return .= '					<span class="h2"><span class="badge bg-red">'.$this->numero.'</span>';
		$return .= '					'.$this->label.'</span>';
		$return .= '					</span>';
		$return .= '			        <input type="checkbox" data-toggle="toggle" data-size="small" disabled  data-style="pull-right" '.$checked.' >';
		$return .= '            </div>';
		$return .= '			<div class="left col-xs-12">';
		$return .= '				<p class="searchable">'.ucfirst(fn_GetTranslation("carte")).' : '.$labelcarteid.' ('.$this->carteid.')</p>';
		$return .= '				<p class="searchable">'.ucfirst(fn_GetTranslation("btn")).' : '.($this->no+1).' ('.$this->xmlid.')</p>';
		$return .= '				<p class="searchable">'.ucfirst(fn_GetTranslation("type")).' : '.$labeltype.' ('.$this->type.')</p>';		
		$return .= '			</div>';
		$return .= '			<div class="right col-xs-4 text-center">';
		//$return .= '				<img src="'.$this->picto.'" alt="" class="img-circle img-responsive">';
		$return .= '			</div>';
		$return .= '		</div>';
		$return .= '	</div>';
		$return .= '</a>';
		echo $return;
	}
	public function configpushoncard (){
		// Instanciation de la carte associées
		$model = fn_GetModel("carte", $this->carteid);
		$carte = new $model($this->carteid);
		$errorcode=0;
	
		$urlbase = $carte->geturl().'protect/assignio/assign1.htm';
		$urlbase .= '?input='.($this->no);
		$url  = '&inputname='.urlencode($this->label);
		list($statusHttp, $responseHttp) = fn_GetContent($urlbase.$url);
		if ( $responseHttp === false ) {
			$status = fn_GetTranslation('update_status_ko');
			$errorcode++;
		}
		else
			$status = fn_GetTranslation('update_status_ok');
		$message = fn_GetTranslation('update_push', "name", __class__, $this->no, $status).'<br/>';
		fn_Trace("Carte ".$carte->numero." : ".urldecode($urlbase.$url), "config_carte", $carte->timezone);

		// config du push (server)
		$urlbase = $carte->geturl().'protect/settings/push1.htm';
		$urlbase .= '?channel='.($this->no);
		$url  = '&server='.$_SERVER["HTTP_HOST"];
		$url .= '&port='.$_SERVER["SERVER_PORT"];
		$url .= '&enph=1';
		list($statusHttp, $responseHttp) = fn_GetContent($urlbase.$url);
		if ( $responseHttp === false ) {
			$status = fn_GetTranslation('update_status_ko');
			$errorcode++;
		}
		else
			$status = fn_GetTranslation('update_status_ok');
		$message .= fn_GetTranslation('update_push', "server", __class__, $this->no, $status).'<br/>';
		fn_Trace("Carte ".$carte->numero." : ".urldecode($urlbase.$url), "config_carte", $carte->timezone);
	
		// config du push close
		$url  = '&cmd1='.urlencode(dirname($_SERVER["REQUEST_URI"]).'/?app=Ws&carteid='.$this->carteid.'&message='.$this->xmlid.'%20Close');
		list($statusHttp, $responseHttp) = fn_GetContent($urlbase.$url);
		if ( $responseHttp === false ) {
			$status = fn_GetTranslation('update_status_ko');
			$errorcode++;
		}
		else
			$status = fn_GetTranslation('update_status_ok');
		$message .= fn_GetTranslation('update_push', "Close", __class__, $this->no, $status).'<br/>';
		fn_Trace("Carte ".$carte->numero." : ".urldecode($urlbase.$url), "config_carte", $carte->timezone);

		// config du push open
		$url  = '&cmd2='.urlencode(dirname($_SERVER["REQUEST_URI"]).'/?app=Ws&carteid='.$this->carteid.'&message='.$this->xmlid.'%20Open');
		list($statusHttp, $responseHttp) = fn_GetContent($urlbase.$url);
		if ( $responseHttp === false ) {
			$status = fn_GetTranslation('update_status_ko');
			$errorcode++;
		}
		else
			$status = fn_GetTranslation('update_status_ok');
		$message .= fn_GetTranslation('update_push', "Open", __class__, $this->no, $status).'<br/>';
		fn_Trace("Carte ".$carte->numero." : ".urldecode($urlbase.$url), "config_carte", $carte->timezone);

		// config du assign output
		$urlbase = $carte->geturl().'protect/assignio/assign1.htm';
		$urlbase .= '?input='.($this->no);
		$url  = '&mode='.$this->mode;
		if ($this->inv == "on")
			$url  .= '&inv='.$this->inv;
		$url  .= '&cnt='.$this->linktocnt;
		
		// Boucle sur les relais en output
		$relais_output=explode(',',$this->linktorelay);
		foreach($relais_output as $relai_output) {
			$url  .= '&l'.$relai_output.'=1';
		}
		list($statusHttp, $responseHttp) = fn_GetContent($urlbase.$url);
		if ( $responseHttp === false ) {
			$status = fn_GetTranslation('update_status_ko');
			$errorcode++;
		}
		else
			$status = fn_GetTranslation('update_status_ok');
		$message .= fn_GetTranslation('update_push', "assign out", __class__, $this->no, $status).'<br/>';
		fn_Trace("Carte ".$carte->numero." : ".urldecode($urlbase.$url), "config_carte", $carte->timezone);

		return array($errorcode, $message);
	}
  
  
	
	// asjson
	// Renvoi un tableau du status du btn pouvant être encodé en json 
	public function asjson($detail = false) {
		if ( $this->active == 'on' ) {
			if ( $this->carteid == "" )
				$this->mysql_load();
			$txt = "";
			$image = "";
			if ( $this->valeur == '0' ) {
				$image = $this->imagedn;
				$txt   = $this->messagedn;
			}
			else {
				$image = $this->imageup;
				$txt   = $this->messageup;
			}
			if ( strlen($txt) == 0 )
				$txt = "#N/A";
			if ( strlen($image) == 0 )
				$image = "#N/A";
			if ( $this->valeur == 0 )
				$txt = str_replace("%etat%", fn_GetTranslation('etat_close'), $txt);
			else
			 	$txt = str_replace("%etat%", fn_GetTranslation('etat_open'), $txt);
     		$txt = str_replace("%label%", $this->label, $txt);
			
			if ( $detail ) 
				$return = array("numero" => $this->numero, "label" => $this->label, "valeur" => $this->valeur, "image" => $image, "txt" => ($txt));
			else
				$return = array("numero" => $this->numero, "valeur" => $this->valeur, "image" => $image, "txt" => ($txt));
		}
		return $return;
	}
	public function asstring() {
		$Msg = "";
		if ( $this->active == "on" )
		{
		if ($this->valeur == 0) {
			$Msg=$this->messageup;
			$Msg = str_replace("%etat%", fn_GetTranslation('etat_close'), $Msg);
		}
		else {
			$Msg=$this->messagedn;
			$Msg = str_replace("%etat%", fn_GetTranslation('etat_open'), $Msg);
		}
		
		$Msg = $this->label . ' ' . str_replace("%label%", $this->label, $Msg);
		}
		if ($Msg!='')
			return $Msg.PHP_EOL;
		else
			return '';
	}
	
	public function GetState()
	{	
		$model = fn_GetModel("carte", $this->carteid);
		$carte = new $model($this->carteid);
		$carte->get_status();
		$this->getxmlvalue($carte->status);
		return $this->valeur;
	}	
	
	public function SetOn() {
		if ($this->type == "F")
			return $this->SetSwitch();
		else {
			$ErrorMsg = "";
			$StatusMsg = "";
			$model = fn_GetModel("carte", $this->carteid);
			$carte = new $model($this->carteid);
			list($urlRelai, $statusHttpRelai, $responseHttpRelai) = $carte->SetBtn($this->no, 1);
			
			if ($responseHttpRelai != False && $statusHttpRelai == "200") {
				if ( $this->messageup == "" )
					$StatusMsg = fn_GetTranslation('relai_on', $carte->label, $this->label);
				else
					$StatusMsg = $this->messageup;
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
			list($urlRelai, $statusHttpRelai, $responseHttpRelai) = $carte->SetBtn($this->no, 0);
			if ($responseHttpRelai != False && $statusHttpRelai == "200") {
				if ( $this->messagedn == "" )
					$StatusMsg = fn_GetTranslation('relai_off', $carte->label, $this->label);
				else
					$StatusMsg = $this->messagedn;
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
			if ( $this->messageup == "" )
				$StatusMsg = fn_GetTranslation('relai_imp', $carte->label, $this->label);
			else
				$StatusMsg = $this->messageup;
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
	
	
}
?>