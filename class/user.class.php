<?php
/*----------------------------------------------------------------*
* Titre : user.php                                                *
* Classe de user                                                  *
*----------------------------------------------------------------*/
class user extends top
{
	public $pass, $apikey, $mail, $gsm, $smsenable, $dateinscription, $actif, $clefactivation, $privilege, $timezone, $pushto, $notifier;
	public $passmd5;

	
	public function __construct($numero="", $info = null)
	{
		parent::__construct($numero, $info);
		if ( ! isset($this->clefactivation) || $this->clefactivation == "" )
		{
			$caracteres = array("a", "b", "c", "d", "e", "f", 0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
			$caracteres_aleatoires = array_rand($caracteres, 10);
			$this->clefactivation = "";
			foreach($caracteres_aleatoires as $i)
			{
				$this->clefactivation .= $caracteres[$i];
			}
		}
		if ( ! isset($this->dateinscription) || $this->dateinscription == "" )
			$this->dateinscription = date("Y-m-d");
		if ( ! isset($this->privilege) ) 
			$this->privilege=100;
		if ( ! isset($this->timezone) || $this->timezone=="#N/A") 
			$this->timezone="Europe/Paris";	
		return $this;
	}
	public function del()
	{
		fn_DelAuthUser($this->numero);
		return parent::del();
	}
	public function form($page = null) {	
		$return  =  fn_HtmlStartPanel($this->label, ucfirst(fn_GetTranslation(__class__)), __class__, $this->numero, "x_panel_60");
		if ($page!="Setup") {
			$return .= '<form name="ajaxform" id="ajaxform" class="form-horizontal form-label-left" novalidate>';
			if (isset($_REQUEST["HTTP_REFERER"]))
				$return .= fn_HtmlHiddenField ('HTTP_REFERER',urlencode($_REQUEST["HTTP_REFERER"]));
  			elseif (isset($_SERVER["HTTP_REFERER"]))
  				$return .=  fn_HtmlHiddenField('HTTP_REFERER',urlencode($_SERVER["HTTP_REFERER"]));
  			else
  				$return .=  fn_HtmlHiddenField('HTTP_REFERER', '');
  			$return .=  fn_HtmlHiddenField('class',__class__);
        }
			if (isset($this->numero)) {
  				$return .=  fn_HtmlHiddenField('numero',$this->numero);
			}
		$return .= fn_HtmlHiddenField('action','');
		$return .= fn_HtmlInputField('label', $this->label, 'text', 'login', 'user.label', "", true);
		$return .= fn_HtmlInputField('pass', $this->pass, 'password', 'password', 'user.pass', "", true,false, false, false, '' );
		$return .= fn_HtmlInputField('passconfirm', $this->pass, 'password', 'confirm_password', 'user.passconfirm', "", true,false, false, false, '');
		$return .= fn_HtmlInputField('mail', $this->mail, 'email', 'email', 'user.mail', "", true);
		if (isset($GLOBALS["config"]->users)) {
			$return .= '<div class="form-group">';
			$return .= '	<label class="control-label control-label col-md-3 col-sm-3 col-xs-12" for="BT_Resend">';
			$return .= '		<span class="span-label">'.ucfirst(fn_GetTranslation("activationmail")).'</span>';
			$return .= '	</label>';
			$return .= fn_Help('user.activationmail');
			$return .= '	<div id="input-btns" class="col-md-6 col-sm-6 col-xs-12">';
			$return .= '		<button id="BT_Resend" class="actionbutton btn btn-primary col-sm-6 col-xs-12" onclick="" name="BT_Resend" type="">'.fn_GetTranslation("resendactivationmail").'</button>';
			$return .= '	</div>';
			$return .= '</div>';
		}
		$return .= fn_HtmlInputField('gsm', $this->gsm, 'tel', 'gsm', 'user.gsm', "", false, false, false, false, 'data-validation="custom" data-validation-regexp="^(0|\+33)[1-9]([-. ]?[0-9]{2}){4}$"' 
		/*'(\+\d+(\s|-))?0\d(\s|-)?(\d{2}(\s|-)?){4}'*/);
		$return .= fn_HtmlSelectField('timezone', 'timezone', 'user.timezone');
		$return .= fn_HtmlBinarySelectField('smsenable', $this->smsenable, 'smsenable', 'user.smsenable');
		$return .= "<!-- ".count($GLOBALS["config"]->users->user)." -->";
			if ( ! isset($GLOBALS["config"]->users->user) || (count($GLOBALS["config"]->users->user)<=1))
				$return .= fn_HtmlSelectField('privilege', 'privilege', 'user.privilege','',false,false,true);
			else
				$return .= fn_HtmlSelectField('privilege', 'privilege', 'user.privilege',"",false,false,false);
			$return .= fn_HtmlSelectField('pushto', 'push_account', 'user.pushto', '', true);
			$return .= fn_HtmlBinarySelectField('notifier', $this->notifier, 'notify_connexion', 'user.notifier');
		if (isset($GLOBALS["config"]->users)) {

			if ( isset($_SESSION['Privilege']) && 
				 $_SESSION['Privilege'] >= (int)$GLOBALS["config"]->access->activercompte && 
				 (isset($GLOBALS["config"]->users->user) && (count($GLOBALS["config"]->users->user)>1)))
			{
				$return .= fn_HtmlBinarySelectField('actif', $this->actif, 'activate_account', 'user.actif');
			}
			else
				$return .= fn_HtmlHiddenField("actif", $this->actif);
		}
		else
			$return .= fn_HtmlHiddenField("actif", "on");
		$return .= fn_HtmlHiddenField("clefactivation", $this->clefactivation);
		$return .= fn_HtmlHiddenField("dateinscription", $this->dateinscription);
		$return .= fn_HtmlHiddenField("passmd5", $this->pass);
		return $return;
	}
	public function advancedobject() { 
		$return  = fn_HtmlStartPanel(ucfirst(fn_GetTranslation('authorizations')), fn_GetTranslation('authorizations_foruser',$this->label), "habilitations", "none", "x_panel_20");
		if ( isset($this->numero) && $this->numero != "" ) {
			foreach(array("carte", "relai", "btn", "cnt", "an", "razdevice", "espdevice", "variable", "vartxt", "page", "camera", "scenario", "graphique", "lien") as $classitem) {
				$return .='				<a class="animated fadeInDown btn-app btn" href="?app=Mn&amp;page=UpdateDroits&amp;class='.$classitem.'&amp;userid='.$this->numero;
            	if ( isset($_REQUEST["http_referer"]) )
            		$return .= '&http_referer='.urlencode($_REQUEST["http_referer"]);
				$return .= '">';
				if ( isset($GLOBALS["config"]->{$classitem."s"}) )
					$return .='					<span class="badge bg-red">'.$GLOBALS["config"]->{$classitem."s"}->{$classitem}->Count().'</span>';
				else
					$return .='					<span class="badge bg-red">0</span>';
				$return .='						<i class="fa ' . fn_GetTranslation('fa-'.$classitem) . '"></i>';
				$return .='						'.ucfirst(fn_GetTranslation($classitem.'s'));
				$return .='					</a>';
			}
		}
		$return .= '	</div><!-- /x-content -->';
		$return .= '</div><!-- /x-panel -->';
		if (!isset($GLOBALS["config"]->users))
			$return = '';
		return $return;
	}
	
	public function js($page= null)
	{
		$oblig = '';
		if (isset($this->numero))
			$oblig=', required';
		$return  ='AjaxLoadSelectJson("timezone", "class=timezone", false, "'.$this->timezone.'" );';
		if (isset($GLOBALS["config"]->users)) {
		$return .='AjaxLoadSelectJson("privilege", "class=modelprivilege", false, "'.$this->privilege.'");';
		$return .='AjaxLoadSelectJson("pushto", "class=pushto", false, "'.$this->pushto.'" );';
		}
		$return .='
				/* Règles de validation */
				var configvalidate = {
					// ordinary validation config
					form : \'#ajaxform\',
					// reference to elements and the validation rules that should be applied
					validate : {
						\'#label\' : {
						    validation : \'length\',
							length : \'4-20\'
						},
						\'#pass\' : {
							validation : \'length'.$oblig.'\',
							length : \'min6\'
						},
						\'#passconfirm\' : {
							validation : \'length, confirmation'.$oblig.'\',
							length : \'min6\',
							confirm : \'pass\'
						}
					}
				};';
		return $return;
	}
	public function receive_form($list_data = null)
	{
		list($status, $message) = parent::receive_form(array("label", "pass", "apikey", "passmd5", "mail", "gsm", "smsenable", "dateinscription", "clefactivation", "privilege", "actif", "timezone", "pushto", "notifier"));
		if ( $this->actif=="")
			$this->actif="off";
		if ( $this->smsenable=="")
			$this->smsenable="off";
		if ( $this->notifier=="")
			$this->notifier="off";
		if ( $this->pushto=="")
			$this->pushto="off";
		if ( $this->privilege=="")
			$this->privilege="100";
		if ( $this->pass != $this->passmd5 ) {
			$this->pass = md5($this->pass);
		}
		return array($status, $message);
		}
	public function save($list_data = null) {
		$result=array();	
		$doc = new domDocument();
		$doc->preserveWhiteSpace = FALSE;
		$doc->formatOutput = TRUE;
		$doc->load($GLOBALS["config_file"]);
		$xpathdoc= new DOMXPath($doc);
		$List = $xpathdoc->query('//users/user');
		if ( $List->length == 0 ) $this->privilege = 100;
		$this->apikey = md5((string)$this->label).$this->pass;
		
		
		$result = parent::save(array("label", "pass", "apikey", "mail", "gsm", "smsenable", "dateinscription", "clefactivation", "privilege", "actif", "timezone", "pushto", "notifier"));
		if ( $this->creation ) {
			fn_InitAuthUser($this->numero, $this->privilege);
		}	
		$result = array_merge($result, $this->sendsubscribemail());
		return $result;
	}
	
	public function sendsubscribemail() {
		$result=array();
		if ( $this->actif == "off" && $this->creation ) {
			/** CONFIGURATION **/
			$de_nom = $GLOBALS["config"]->general->nameadmin; //Nom de l'envoyeur
			$de_mail = $GLOBALS["config"]->general->mailadmin; //Email de l'envoyeur
			$vers_nom = $this->label; //Nom du receveur
			$vers_mail = $this->mail; //Email du receveur
			$sujet = fn_GetTranslation('subject_activate_account');
			//Message :
			$corps = fn_GetTranslation('to_validate_account')." :";
			$corps .= "http://" . $_SERVER["SERVER_NAME"]."".dirname($_SERVER["SCRIPT_NAME"])."/?app=Mn&amp;page=ActiverCompte&amp;numero=". $this->numero;
			$corps .= "&amp;clef=" . $this->clefactivation;
			/** Envoi du mail **/
			$entete = "MIME-Version: 1.0\r\n";
			$entete .= "Content-type: text/html; charset=iso-8859-1\r\n";
			$entete .='Content-Transfer-Encoding: 8bit';
			$entete .= "To: $vers_nom <$vers_mail>\r\n";
			$entete .= "From: $de_nom <$de_mail>\r\n";
			if(!@mail($vers_mail, $sujet, $corps, $entete))
			{
				$result[] = array('messagemail' => fn_GetTranslation('error_on_activate_mail'));
				$result[] = array('messagemail' => fn_GetTranslation('contact_admin_to_activate'));
			}
			else
			{
				// Message de confirmation
				$result[] = array('messagemail' => fn_GetTranslation('mail_send_to_activate', $this->mail));
			}
		}		
		return $result;
	
	}

	public function disp_list() {
		//$beginletter="", $searchstr=""
		//if ((($beginletter=='') || ($beginletter == substr(strtoupper($this->label),0,1))) && 
		//	(($searchstr=='') || (stristr($this->label, $searchstr) != false || stristr($this->mail, $searchstr) != false)))			 {
				$checked="";
				$xpath = "//modelprivileges/modelprivilege[@numero='".$this->privilege."']";
				$privilege = fn_GetByXpath($xpath, 'bal', 'label');
				if ($this->actif=="on") $checked="checked";
				$return  = '<a href="?app=Mn&page=Add&class='.__class__.'&numero='.$this->numero.'" class="'. $GLOBALS["classDispList"] .' btn-app">';
				$return .= '	<div class="ribbon blue"><span>'.$privilege.'</span></div>';
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
				$return .= '				<p class="searchable">'.$this->mail.'</p>';
				$return .= '				<p class="searchable">'.$this->gsm.'</p>';
				$return .= '			</div>';
				$return .= '		</div>';
				$return .= '	</div>';
				$return .= '</a>';
				echo $return;
			//}
	}
	
}
?>