<?php
/*----------------------------------------------------------------*
* Titre : an.php                                                  *
* Classe de an                                                    *
*-----------------------------------------------------------------*/
class an extends top
{
	public $type, $compensation, $displayformat, $gaugemin, $gaugemax, $valeur, $valconv, $active, $precision, $carteid, $no, $update_time, $xmlid;
	public $creation;
	public function __construct($numero = "", $info = null)
	{	parent::__construct($numero, $info);
		$this->creation = false;
		if ( ! isset($this->label) )
		{	
			$this->creation = true;
			$this->type = "B";
			$this->label = fn_GetTranslation(__class__)." ".$this->numero;
			$this->active = 'on';
			$this->precision = '';
		}
		if ( $this->xmlid == "" && isset($info->flag_analog) )
		{
			if ( $info->flag_analog == 1 )
				$this->xmlid = 'analog'.$this->no;
			else
				$this->xmlid = 'an'.($this->no+1);
		}
		return $this;
	}
	public function del()
	{
		fn_DelAuth(get_class($this), $this->numero);
		return parent::del();
	}
	public function update()
	{
		if ( $this->compensation == $this->numero )
			$this->compensation = "";
		if ( ! isset($this->active) )
			$this->active = 'off';
		if ( ! isset($this->precision) )
			$this->precision = "";
		parent::update();
		if ( isset($this->flag_analog) )
			unset($this->flag_analog);
		if ( isset($this->flag_indice) )
			unset($this->flag_indice);
	}
	public function form($page=null)
	{	$return  =  fn_HtmlStartPanel($this->label, ucfirst(fn_GetTranslation(__class__)), __class__,$this->numero);
		$return .= '<form name="ajaxform" id="ajaxform" class="form-horizontal form-label-left">';
		$return .= fn_HtmlHiddenField('class',__class__);
		if (isset($this->numero)) {
			$return .=  fn_HtmlHiddenField('numero',$this->numero);
		}
    	$return .= fn_HtmlHiddenField('action','');
		$return .= fn_HtmlStartFieldset(ucfirst(fn_GetTranslation('display')), 'GrpDisplay');
		$return .= fn_HtmlInputField('label', $this->label, 'text', 'an_name', 'an.label');
		$return .= fn_HtmlInputField('precision', $this->precision, 'text', 'precision', 'an.precision');
		$return .= fn_HtmlSelectField('displayformat', 'an_displayformat', 'an.displayformat');
		$return .= fn_HtmlInputField('gaugemin', $this->gaugemin, 'text', 'an_gaugemin', 'an.gaugemin');
		$return .= fn_HtmlInputField('gaugemax', $this->gaugemax, 'text', 'an_gaugemax', 'an.gaugemax');
 		$return .= fn_HtmlEndFieldset();
		$return .= fn_HtmlStartFieldset(ucfirst(fn_GetTranslation('referer')), 'GrpReferer', true);
		//$OnChange="AjaxLoadSelectJson('no', 'class=".get_class($this)."&carteid='+this.options[this.options.selectedIndex].value, false, '".$this->no."' );";
		$return .= fn_HtmlBinarySelectField('active', $this->active, 'active', 'an.active',true);
		$return .= fn_HtmlSelectField('carteid', 'carte', 'an.carteid');
		$return .= fn_HtmlSelectField('no', 'an_no', 'an.no');
		//$return .= fn_HtmlInputField('labelipx', $this->labelipx, 'text', 'nom_ipx', 'btn.labelipx', '');
		$return .= fn_HtmlInputField('xmlid', $this->xmlid, 'text', 'nom_ipx', 'an.labelipx', '', false, false, false, true);
		//'AjaxCompensation(this.options[this.options.selectedIndex].value, '.$this->carteid.');'
		$return .= fn_HtmlSelectField('type', 'type', 'an.type');
	 	$return .= fn_HtmlEndFieldset();
		$return .= fn_HtmlStartFieldset(ucfirst(fn_GetTranslation('compensation')), 'GrpCompensation', true);	
		$return .= fn_HtmlSelectField('compensation', 'compensation', 'an.compensation');
		$return .= fn_HtmlEndFieldset();
		return $return;
	}
	public function js()
	{
		$return = '
			jQuery("select#carteid").change(function(){
				var carteid = jQuery("select#carteid option:selected").attr(\'value\');
				var model = AjaxGetModel("carte", carteid);
				console.log(model+"("+carteid+")");
				if (model=="carterasponewire") {
					$("#no").parent().parent().addClass("hidden");
				}
				else {
					$("#no").parent().parent().removeClass("hidden");
					AjaxLoadSelectJson("no", "carteid="+carteid+"&class='.__class__.'", false, "'.$this->no.'" );
				}
					
			});
			AjaxLoadSelectJson("carteid", "class=carte", false, "'.$this->carteid.'" );
	        AjaxLoadSelectJson("no", "carteid='.$this->carteid.'&class='.get_class($this).'", false, "'.$this->no.'" );
            AjaxLoadSelectJson("type", "class=typean", false, "'.$this->type.'" );
            AjaxLoadSelectJson("displayformat", "class=andisplayformat", false, "'.$this->displayformat.'" );

			jQuery("select#no").change(function(){
				var carteid = jQuery("select#carteid option:selected").attr(\'value\');
				var no = jQuery("select#no option:selected").attr(\'value\');
				if (typeof(carteid)!="undefined" && typeof(no)!="undefined") {
					jQuery("#xmlid").val("analog"+(no));
					jQuery("#BT_Envoyer").prop("disabled", false);
				}
			});

			jQuery("select#displayformat").change(function(){
				var displayformat = jQuery("select#displayformat option:selected").attr(\'value\');
				if (typeof(displayformat)!="undefined" && displayformat=="justgauge") {
					jQuery("#gaugemin").parent().parent().removeClass("hidden");
					jQuery("#gaugemin").prop("disabled", false);
					jQuery("#gaugemax").parent().parent().removeClass("hidden");
					jQuery("#gaugemax").prop("disabled", false);
				}
				else {
					jQuery("#gaugemin").parent().parent().addClass("hidden");
					jQuery("#gaugemin").prop("disabled", true);
					jQuery("#gaugemax").parent().parent().addClass("hidden");
					jQuery("#gaugemax").prop("disabled", true);
					
				}
			});

			';
			
			//if ($this->carteid != "")  
		    //$return .= 'AjaxCompensation(\''.$this->type.'\', '.$this->carteid.');';
			$return .= '	/* Règles de validation */
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
						},
						\'#precision\' : {
							allowing : \'range[1;10]\',
							validation : \'number\',
							optional : \'true\'}
					}
				};

			';
		return $return; 
	}
	public function receive_form($list_data = null)
	{
		list($status, $message) = parent::receive_form(array("label", "type", "displayformat", "gaugemin", "gaugemax", "compensation", "carteid", "no", "active", "precision", "xmlid"));
		/*if ( $this->compensation == $this->numero )
		{
			$this->compensation = "";
			$status = false;
			//$message = fn_GetTranslation('compensation_loop');
		}
		*/
		return array($status, $message);
	}
	public function save($list_data = null) {
		if ($this->xmlid == "")
			$this->xmlid ='analog'.$this->no;
		return parent::save(array("label", "type", "displayformat", "gaugemin", "gaugemax", "carteid", "no", "compensation", "active", "precision", "xmlid"));
		
	}
	public function verif_before_del() {
		return true;
	}
	public function disp($commandactive=true, $jsremove=false) {
		$classcss='noteditable';
		if ($jsremove)
			$classcss='';	
		if ( $this->active == 'on' ) {
			$xpathModele='//type'.get_class($this).'s/type'.get_class($this).'[@numero="'.$this->type.'"]';
			$type = $GLOBALS["config"]->xpath($xpathModele);
			$xpathModele='//unites/unite[@numero="'.$type[0]->{'unit'}.'"]';
			$unite = $GLOBALS["config"]->xpath($xpathModele);
			$unite = addcslashes($unite[0]->{'label'}, "'");
			$return = '<div class="Container animated flipInY ' . $GLOBALS["config"]->csscontainer .' ' .  $classcss.'" data-id="'.$this->config_class.'_'.$this->numero.'">';
			$return.=    '<div class="Entete '.get_class($this).'_label_'.$this->numero.'">'.$this->label.'</div>';
			switch ($this->displayformat) {
				case "justgauge": 
					$return.=    '<div class="DispValue">';
					$return.=    '<div class="justgauge gauge '.get_class($this).'_valconv_'.$this->numero.'" data-min="'.$this->gaugemin.'" data-max="'.$this->gaugemax.'"></div>';
					$return.=    '</div>';
					break;	
				default:
					$return.=    '<div class="DispValue">';
					$return.=       '<div class="Valeur">';
					$return.=          '<span class="'.get_class($this).'_valconv_'.$this->numero.'">'.$this->valconv.'</span>';
					$return.=          '<div class="UnitAn">';
					$return.=               '<span class="'.get_class($this).'_unit_'.$this->numero.'">'.$unite.'</span>';
					
					$return.=          '</div>';
					$return.=       '</div>';
					$return.=    '</div>';
			}
			$return.=    '<div class="Caption an_txt_'.$this->numero.' Visibility-hidden"></div>';
			$return.='<i class="js-remove fa fa-trash-o fa-2x"></i>';	
			$return.='</div>';

			return $return;
		}
	}
	public function getxmlvalue($xmlStatus) {

		parent::getxmlvalue($xmlStatus);
		if ( isset($xmlStatus->{$this->xmlid}) )
			$this->valeur = (string)$xmlStatus->{$this->xmlid};
		else
			$this->valeur = "";
		$val=$this->valeur;
			$xpathModele='//type'.get_class($this).'s/type'.get_class($this).'[@numero="'.$this->type.'"]';
			$type = $GLOBALS["config"]->xpath($xpathModele);
			if ( $this->compensation != $this->numero && $this->compensation != "" )
			{
				$current_compensation = new an($this->compensation);
				$current_compensation->getxmlvalue($xmlStatus);
				$compensation = $current_compensation->valeur;
			}
			if ($type != null)
				$this->valconv = eval($type[0]->{'formule'});
		return $this->valconv;
	}
	public function asxml($detail = false) {
		$return = "";
		if ( $this->active == 'on' )
		{
			if ( $this->carteid == "" )
				$this->mysql_load();
			$return = "<".get_class($this)." numero='".$this->numero."'>";
			if ( $this->precision != "" )
				$this->valconv = round ($this->valconv, (int) $this->precision );
			$return .= "<valconv>".$this->valconv."</valconv>" ;
			$return .= "<valeur>".$this->valeur."</valeur>" ;
			if ( $detail )
			{
				$return .= "<label>".$this->label."</label>";
				$xpathModele='//type'.get_class($this).'s/type'.get_class($this).'[@numero="'.$this->type.'"]';
				$type = $GLOBALS["config"]->xpath($xpathModele);
				$xpathModele='//unites/unite[@numero="'.$type[0]->{'unit'}.'"]';
				$unite = $GLOBALS["config"]->xpath($xpathModele);
				$unite = addcslashes($unite[0]->{'label'}, "'");
				$return .= "<unite>".$unite."</unite>";
			}
			$return .= "</".get_class($this).">";
		}
		return $return;
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
		$return .= '				<p class="searchable">'.ucfirst(fn_GetTranslation("an")).' : '.($this->no+1).' ('.$this->xmlid.')</p>';
		$return .= '				<p class="searchable">'.fn_GetTranslation("type").' : '.$labeltype.' ('.$this->type.')</p>';		
		$return .= '			</div>';
		//$return .= '			<div class="right col-xs-4 text-center">';
		//$return .= '				<img src="'.$this->picto.'" alt="" class="img-circle img-responsive">';
		//$return .= '			</div>';
		$return .= '		</div>';
		$return .= '	</div>';
		$return .= '</a>';
		echo $return;
	}
	public function asjson($detail = false) {
		if ( $this->active == 'on' ) {
			if ( $this->carteid == "" )
				$this->mysql_load();

			if ( $this->precision != "" )
				$this->valconv = round ($this->valconv, (int) $this->precision );
			if ( $detail ) {
				$xpathModele='//type'.get_class($this).'s/type'.get_class($this).'[@numero="'.$this->type.'"]';
				$type = $GLOBALS["config"]->xpath($xpathModele);
				$xpathModele='//unites/unite[@numero="'.$type[0]->{'unit'}.'"]';
				$unite = $GLOBALS["config"]->xpath($xpathModele);
				$unite = addcslashes($unite[0]->{'label'}, "'");
				$return[$this->numero] = array("label" => $this->label, "unite" => $unite, "valeur" => $this->valeur, "valconv" => $this->valconv);
			}
			else
				$return[$this->numero] = array("valeur" => $this->valeur, "valconv" => $this->valconv);
		}
		return $return;
	}
	
	public function asstring() {
		$Msg = "";
		if ( $this->active == "on" ) {
			$xpathModele='//type'.get_class($this).'s/type'.get_class($this).'[@numero="'.$this->type.'"]';
			$type = $GLOBALS["config"]->xpath($xpathModele);
			$xpathModele='//unites/unite[@numero="'.$type[0]->{'unit'}.'"]';
			$unite = $GLOBALS["config"]->xpath($xpathModele);
			$unite = addcslashes($unite[0]->{'label'}, "'");
			
			$Msg = $this->label . ' ' . $this->valconv . ' ' . $unite;
		}
		return $Msg.PHP_EOL;
	}
	
	public function GetState()
	{	
		$Msg = "";
		$model = fn_GetModel("carte", $this->carteid);
		$carte = new $model($this->carteid);
		$carte->get_status();
		$this->getxmlvalue($carte->status);
		if ( $this->active == "on" ) {
			return $this->valconv;
		}
		else
			return 'NaN';
	}	

		
	
}
?>