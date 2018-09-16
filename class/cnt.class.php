<?php
/*----------------------------------------------------------------*
* Titre : cnt.php                                            *
* Classe de cnt                                              *
*-----------------------------------------------------------------*/
class cnt extends top
{
	public $carteid, $no, $valeur, $valconv, $unite, $formule, $active, $update_time, $xmlid;
	public $creation;
	public function __construct($numero = "", $info = null)
	{
		parent::__construct($numero, $info);
		$this->creation = false;
		if ( ! isset($this->label) ) {	
			$this->creation = true;
			$this->label = ucfirst(fn_GetTranslation(__class__));
			$this->formule = '$cnt';
			$this->unite = 'sans';
			$this->active = 'on';
		}
		if ( $this->xmlid == "" && isset($info->flag_indice) )
		{
			if ( $info->flag_indice == 1 )
				$this->xmlid = 'count'.($this->no+1);
			else
				$this->xmlid = 'count'.$this->no;
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
		
		$model = fn_GetModel("carte", $this->carteid);
		$carte = new $model($this->carteid);
		$carte->get_status();
		$this->getxmlvalue($carte->status);

    	$return .= fn_HtmlHiddenField('action','');
		$return .= fn_HtmlStartFieldset('Display', 'GrpDisplay');
		$return .= fn_HtmlInputField('label', $this->label, 'text', 'cnt_label', 'cnt.label', '',true);
		$return .= fn_HtmlSelectField('unite', 'cnt_unite', 'cnt.unite');
 		$return .= fn_HtmlEndFieldset();
		$return .= fn_HtmlStartFieldset(ucfirst(fn_GetTranslation('referer')), 'GrpReferer', true);
		$return .= fn_HtmlBinarySelectField('active', $this->active, 'active', 'cnt.active',true);
		$return .= fn_HtmlSelectField('carteid', 'carte', 'cnt.carteid');
		$return .= fn_HtmlSelectField('no', 'cnt_no', 'cnt.no');
		$return .= fn_HtmlInputField('inputxmlid', $this->xmlid, 'text', 'nom_ipx', 'cnt.xmlid', '', false, false, false, true,"","xmlid");
		$return .= fn_HtmlSelectField('selxmlid', 'nom_ipx', 'cnt.xmlid', '', false, false, false, '', '', 'xmlid');
		$return .= '	<div class="form-group">';
		$return .= '		<label class="control-label control-label col-md-3 col-sm-3 col-xs-12" for="BT_ConfigPushOnCard">';
		$return .= '			<span class="span-label">&nbsp</span>';
		$return .= '		</label>';
		$return .= 			fn_Help('cnt.configcnt');
		$return .= '		<div id="input-btns" class="col-md-6 col-sm-6 col-xs-12">';
		$return .= '			<button id="BT_ConfigPushOnCard" class="btn btn-primary col-xs-12" onclick="jQuery(\'#action\').val(this.id);" name="BT_ConfigPushOnCard" type="">'.fn_GetTranslation("config_carte", __class__, $this->no, $this->carteid).'</button>';
		$return .= '		</div>';
		$return .= '	</div>';
		
		$return .= fn_HtmlEndFieldset();
		$return .= fn_HtmlStartFieldset(ucfirst('conversion'), 'GrpConversion', true);
		$return .= fn_HtmlInputField('formule', $this->formule, 'text', 'cnt_formule', 'cnt.formule');
		$return .= fn_HtmlInputField('valsetcnt', $this->valeur, 'text', 'value', 'cnt.setcnt', '', false);
		$return .= '<div class="form-group">';
		$return .= '	<label class="control-label control-label col-md-3 col-sm-3 col-xs-12" for="BT_Resend">';
		$return .= '		<span class="span-label"></span>';
		$return .= '	</label>';
		$return .= fn_Help('cnt.setcnt');
		$return .= '	<div id="input-btns" class="col-md-6 col-sm-6 col-xs-12">';
		$return .= '		<button id="BT_GetCnt" class="btn btn-primary col-md-5 col-sm-5 col-xs-12" onclick="jQuery(\'#action\').val(this.id);" name="BT_GetCnt" type="">'.fn_GetTranslation("get").'</button>';
		$return .= '		<button id="BT_SetCnt" class=" btn btn-primary col-md-5 col-sm-5 col-xs-12" onclick="jQuery(\'#action\').val(this.id);" name="BT_SetCnt" type="">'.fn_GetTranslation("set").'</button>';
		$return .= '	</div>';
		$return .= '</div>';		
		
		$return .= fn_HtmlInputField('valconv', $this->valconv, 'text', 'cnt_afterconv', 'cnt.afterconv','',false,false,false,true);
		$return .= fn_HtmlEndFieldset();
		return $return;
	}
	public function js()
	{
		//$OnChangecarteid="AjaxLoadSelectJson('no', 'class=".get_class($this)."&carteid='+this.options[this.options.selectedIndex].value, false, '".$this->no."' );";

	$param="";
	global $filtre_carteid;
	if ($filtre_carteid!="") {
		$param = "&filtre_numero=".$filtre_carteid;
		if ($this->carteid == "")
			$this->carteid = $filtre_carteid;
	}
	// Hidden sur inputxmlid et selxmlid
	$return  = '$("#inputxmlid").parent().parent().addClass("hidden");';
	$return .= '$("#selxmlid").parent().parent().addClass("hidden");';
	if (fn_GetModel('carte', $this->carteid) == 'carteecodev')
		$return .= 'AjaxLoadSelectJson("selxmlid", "carteid='.$this->carteid.'&class='.get_class($this).'", false, "'.$this->xmlid.'" );';
	// Chargement de la liste déroulante des unités
	$return .= 'AjaxLoadSelectJson("unite", "class=unite", false, "'.$this->unite.'" );';
	// Chargement de la liste des cartes
	$return .= 'AjaxLoadSelectJson("carteid", "class=carte'.$param.'", false, "'.$this->carteid.'" );';
	// Chargement de la liste des compteurs disponible pour la carte
	$return .= 'AjaxLoadSelectJson("no", "carteid='.$this->carteid.'&class='.get_class($this).'", false, "'.$this->no.'" );';
	// Lors du changement de numero de compteur, le nom de la balise xmlid est recalculée
	$return .= 'jQuery("#no").change(function(){
				var carteid = jQuery("#carteid option:selected").attr(\'value\');
				var no = jQuery("#no option:selected").attr(\'value\');
				jQuery("#inputxmlid").val("count"+$("#no").val());
				if (typeof(carteid)!="undefined" && typeof(no)!="undefined") 
					jQuery("#BT_Envoyer").prop("disabled", false);
			});';
	
	$return .= 'jQuery("#valconv").addClass("' . get_class($this) . '_valconv_' . $this->numero .'");';
	// Chargement des la liste des compteurs lors du changement de carte
	$return .= 'jQuery("#carteid").change(function(){
					var carteid = jQuery("#carteid option:selected").attr(\'value\');
					
					if (typeof(carteid)!="undefined" ) {
						var model = AjaxGetModel("carte", carteid);
						console.log(model+"("+carteid+") - Rechargement des xmlid");
						var carteid = jQuery("select#carteid option:selected").attr(\'value\');
						if (model=="carteecodev") {
							$("#no").parent().parent().addClass("hidden");
							$("#inputxmlid").parent().parent().addClass("hidden");
							$("#selxmlid").parent().parent().removeClass("hidden");
							$("#inputxmlid").prop("disabled", true);
							$("#selxmlid").prop("disabled", false);
							AjaxLoadSelectJson("selxmlid", "carteid="+carteid+"&class='.get_class($this).'", false, "" );
						}
						else {
							$("#no").parent().parent().removeClass("hidden");
							$("#inputxmlid").parent().parent().removeClass("hidden");
							$("#selxmlid").parent().parent().addClass("hidden");
							$("#selxmlid").prop("disabled", true);
							$("#inputxmlid").prop("disabled", false);
							AjaxLoadSelectJson("no", "carteid="+carteid+"&class='.get_class($this).'", false, "'.$this->no.'" );
						}
					}
				});
			';
		
		$return .= '/* Règles de validation */
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
					\'#formule\' : {
					    validation : \'length\',
						length : \'min4\'
					}
					
				}
			};
		';
		
	
		return $return;
	}
	public function receive_form($list_data = null)
	{
		list($status, $message) = parent::receive_form(array("label", "carteid", "no", "unite", "formule", "active", "xmlid"));
		if ( $this->active=="")
			$this->active="off";		
		return array($status, $message);
	}
	public function save($list_data = null)
	{
		return parent::save(array("label", "carteid", "no", "unite", "formule", "active", "xmlid"));
	}
	public function verif_before_del()
	{
		return true;
	}
	public function disp($commandactive=true, $jsremove=false)
	{
		$classcss='noteditable';
		if ($jsremove)
			$classcss='';		
		if ( $this->active == 'on' )
		{
			$xpathModele='//unites/unite[@numero="'.$this->unite.'"]';
			$unite = $GLOBALS["config"]->xpath($xpathModele);
			$unite = addcslashes($unite[0]->{'label'}, "'");
			$return = '<div class="Container animated flipInY ' . $GLOBALS["config"]->csscontainer .' ' .  $classcss.'" data-id="'.$this->config_class.'_'.$this->numero.'">';
			$return.=    '<div class="Entete '.get_class($this).'_label_'.$this->numero.'" >'.$this->label.'</div>';
			$return.=    '<div class="DispValue">';
			$return.=    	'<div class="Valeur">';
			$return.=    		'<span class="'.get_class($this).'_valconv_'.$this->numero.'" >'.$this->valconv.'</span>';
			$return.=       	'<div class="UnitCnt">';
			$return.=       		'<span class="'.get_class($this).'_unit_'.$this->numero.'">'.$unite.'</span>';
			$return.=       	'</div>';
			$return.= 	 	'</div>';
			$return.= 	 '</div>';
			$return.=    '<div class="Caption '.get_class($this).'_txt_'.$this->numero.' Visibility-hidden"></div>';
			$return.='<i class="js-remove fa fa-trash-o fa-2x"></i>';	
			$return.= '</div>';

			return $return;
		}
	}
	public function getxmlvalue($xmlStatus) {
		parent::getxmlvalue($xmlStatus, $this->xmlid);
		$cnt = $this->valeur;
		$formule=$this->formule;
		$this->valconv = eval('return '.$formule.';');
		return $this->valconv;
	}

	public function asxml($detail = false)
	{
		$return = "";
		if ( $this->active == 'on' )
		{
			if ( $this->carteid == "" )
				$this->mysql_load();
			$return = "<".get_class($this)." numero='".$this->numero."'>";
			$return .= "<valeur>".$this->valeur."</valeur>" ;
			$return .= "<valconv>".$this->valconv."</valconv>" ;
			if ( $detail )
			{
				$return .= "<label>".$this->label."</label>";
				$xpathModele='//unites/unite[@numero="'.$this->unite.'"]';
				$unite = $GLOBALS["config"]->xpath($xpathModele);
				$unite = addcslashes($unite[0]->{'label'}, "'");
				$return .= "<unite>".$unite."</unite>";
			}
			$return .= "</".get_class($this).">";
		}
		return $return;
	}
	
	// asjson
	// Renvoi un tableau du status du cnt pouvant être encodé en json 
	public function asjson($detail = false) {
		$return = "";
		if ( $this->active == 'on' ) {
			if ( $this->carteid == "" )
				$this->mysql_load();

			if ( $detail ) {
				$return .= "<label>".$this->label."</label>";
				$xpathModele='//unites/unite[@numero="'.$this->unite.'"]';
				$unite = $GLOBALS["config"]->xpath($xpathModele);
				$unite = addcslashes($unite[0]->{'label'}, "'");
				$return[$this->numero] = array('label' => $this->label, 'valeur' => $this->valeur, 'valconv' => $this->valconv, 'unite' => $unite);
			}
			else
				$return[$this->numero] = array('valeur' => $this->valeur, 'valconv' => $this->valconv);
		}
		return $return;
	}

	public function update()
	{
		if ( ! isset($this->no) )
		{
			if ( preg_match("/^carte-[0-9]*-".get_class($this)."-(.*)$/", $this->numero, $regs) && is_int($regs[1]) )
			{
				if ( $this->no != $regs[1]-1 )
					$this->no = $regs[1]-1;
			}
		}
		if ( isset($this->type) )
			unset($this->type);
		if ( ! isset($this->formule) )
			$this->formule = '$cnt';
		if ( ! isset($this->unite) || $this->unite == "" )
			$this->unite = 'sans';
		if ( ! isset($this->active) )
			$this->active = 'on';
		if ( ! isset($this->xmlid) )
			$this->xmlid = "";
		parent::update();
		if ( isset($this->flag_indice) )
			unset($this->flag_indice);
	}
	
	public function setvalue($value) {
		// Instanciation de la carte associées
		$model = fn_GetModel("carte", $this->carteid);
		$carte = new $model($this->carteid);
		$errorcode = 0;
		$idcounter = floor($this->no/2)+ (($this->no % 2) * 1);
		$urlbase = $carte->geturl().'protect/assignio/counter' . $idcounter . '.htm?';
		$url  = 'num='.$this->no;
		$url .= '&counter='.$value;
		list($statusHttp, $responseHttp) = fn_GetContent($urlbase.$url);
		if ( $responseHttp === false ) {
			$status = fn_GetTranslation('update_status_ko');
			$message = fn_GetTranslation('error_communication',$this->carteid);
			$errorcode++;
		}
		else {
			$status = fn_GetTranslation('update_status_ok');
			$message = fn_GetTranslation('force_cnt', $this->label, 'C'.$this->carteid.'CNT'.$this->no, $value);
		}
		return array($errorcode, $message);
	}
	
	public function disp_list() {
		$checked="";
		if ($this->active=="on") $checked="checked";
		$xpath  = '//cartes/carte[@numero="'.$this->carteid.'"]';
		$labelcarteid = fn_GetByXpath($xpath, 'bal', 'label');
		$xpath  = '//unites/unite[@numero="'.$this->unite.'"]';
		$labelunite = fn_GetByXpath($xpath, 'bal', 'label');					
		$return  = '<a href="?app=Mn&amp;page=Add&amp;class=' . __class__ . '&amp;numero=' . $this->numero . '&amp;del=true" class="'. $GLOBALS["classDispList"] .'">';
		$return .= '	<div class="ribbon blue"><span>'.$labelunite.'</span></div>';
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
		$return .= '				<p class="searchable">'.fn_GetTranslation("carte").' : '.$labelcarteid.' ('.$this->carteid.')</p>';
		$return .= '				<p class="searchable">'.ucfirst(fn_GetTranslation("cnt")).' : '.($this->no+1).' ('.$this->xmlid.')</p>';
		$return .= '				<p class="searchable">'.fn_GetTranslation("unit").' : '.$labelunite.' ('.$this->unite.')</p>';		
		$return .= '			</div>';
		//$return .= '			<div class="right col-xs-4 text-center">';
		//$return .= '				<img src="'.$this->picto.'" alt="" class="img-circle img-responsive">';
		//$return .= '			</div>';
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
		$idcounter = floor($this->no/2)+ (($this->no % 2) * 1);
		
		$urlbase = $carte->geturl().'protect/assignio/counter'.$idcounter.'.htm?num='.$this->no;
		$url  = '&cname='.urlencode($this->label);
		$url .= '&counter='.$this->valeur; 
		
		list($statusHttp, $responseHttp) = fn_GetContent($urlbase.$url);
		if ( $responseHttp === false ) {
			$status = fn_GetTranslation('update_status_ko');
			$errorcode++;
		}
		else
			$status = fn_GetTranslation('update_status_ok');
		$message = fn_GetTranslation('updatecard', "name", __class__, $this->no, $status).'<br/>';
		fn_Trace("Carte ".$carte->numero." : ".urldecode($urlbase.$url), "config_carte", $carte->timezone);


		return array($errorcode, $message);
	}

	public function asstring() {
		$Msg = "";
		if ( $this->active == "on" ) {
			$xpathModele='//unites/unite[@numero="'.$this->unite.'"]';
			$unite = $GLOBALS["config"]->xpath($xpathModele);
			$unite = addcslashes($unite[0]->{'label'}, "'");
			$Msg = $this->label . ' ' . $this->valconv . ' ' . $unite;
		}
		return $Msg.PHP_EOL;
	}
	
	
}
?>