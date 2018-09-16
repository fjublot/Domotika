<?php
/*----------------------------------------------------------------*
* Titre : scenario.php                                            *
* Classe de scenario                                              *
*----------------------------------------------------------------*/
class scenario extends top
{
	public $code, $image, $asynchrone, $actif;
	public function __construct($numero, $info = null)
	{
		parent::__construct($numero, $info);
		if (file_exists($GLOBALS["root_path"] . "config/" . get_class($this) . "s/" . $this->numero . ".inc"))
		{
			$this->code = implode("", file("config/" . get_class($this) . "s/" . $this->numero . ".inc"));
		}
		
		if (file_exists($GLOBALS["root_path"] . "/config/images/" . $this->image))
			$this->image = "config/images/" . $this->image;
		else
			$this->image = 'images/' . get_class($this) . 's/' . $this->image;
		return $this;
	}
	public function del()
	{
		unlink("config/" . get_class($this) . "s/" . $this->numero . ".inc");
		fn_DelAuth(get_class($this) , $this->numero);
		return parent::del();
	}
	public function form($page=null) {
		$return  =  fn_HtmlStartPanel($this->label, ucfirst(fn_GetTranslation(__class__)), __class__, $this->numero);
		$return .= '		<div class="x_content">';
		$return .= '			<form name="ajaxform" id="ajaxform" class="form-horizontal form-label-left">';
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
		$return .= fn_HtmlInputField('label', $this->label, 'text', 'scenario_name', 'scenario.label', '');
		$return .= fn_HtmlBinarySelectField('actif', $this->actif, 'actif', 'scenario.actif', true);
		$return .= fn_HtmlBinarySelectField('asynchrone', $this->asynchrone, 'scenario_asynchronous', 'scenario.asynchrone');
		//$return .= fn_HtmlSelectField('image', 'picture', 'scenario.image', "UpdateImage(this.id);", false, true);
		$return .= fn_HtmlButtonPicto('image', $this->image, 'picture', 'scenario.image');
		$return .= fn_HtmlTextAreaField('code', $this->code, 'code', 'scenario_code', '',true,fn_GetTranslation('code_scenario'));
		$return .= '<div class="form-group">';
		$return .= '	<label class="control-label control-label col-md-3 col-sm-3 col-xs-12" for="BT_Resend">';
		$return .= '		<span class="span-label"></span>';
		$return .= '	</label>';
		$return .= fn_Help('cnt.setcnt');
		$return .= '	<div id="input-btns" class="col-md-6 col-sm-6 col-xs-12">';
		$return .= '		<button id="BT_RunScenario" class="btn btn-primary col-md-5 col-sm-5 col-xs-12" onclick="jQuery(\'#action\').val(this.id);AjaxAction(\'scenario\', \''.$this->numero.'\')" name="BT_RunScenario" type="">'.fn_GetTranslation("run").'</button>';
		$return .= '	</div>';
		$return .= '</div>';		
		$return .= '<div class="ln_solid"></div>';
		$list_search_info = array('fonction', 'relai', 'btn', 'an', 'cnt', 'variable', 'vartxt');
		$listitems = array();
		
		foreach($list_search_info as $item) {
            $return .= '<div class="form-group">';	
            $return .= '<label for="image" class="control-label control-label col-md-3 col-sm-3 col-xs-12">';
            $return .= '<span class="span-label"></span>';
            $return .= '</label>';
			$return .= fn_Help('scenario.'.$item);
            $return .= '<div class="btn-group controls col-md-6 col-sm-6 col-xs-12">';
     		$return .= '<button id="bt_'.$item .'" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button" aria-expanded="false">';
			$return .= fn_GetTranslation('add_'.$item);
			$return .= '&nbsp;<span class="caret"></span>';
			$return .= '</button>';
            $return .= '<ul id="ul_bt_'.$item.'" role="menu" class="dropdown-menu">';
			if ($item == 'fonction')
				$listitems = fn_GetListHelpFile($item,"array");
			else
				$listitems = fn_GetListItem($item, "", "", "", "");
			foreach($listitems as $listitem) {
                $return .= '<li><a href="#" class="addcode" data-code="'.$listitem["id"].'">'.$listitem["text"].'</a></li>';
			}
                //<li class="divider"></li>
            $return .= '</ul>';
			$return .= '</div>';
			$return .= '</div>';
		}
		return $return;
	
	}
	public function js() { 
		//$return  = 'AjaxLoadSelectJson("image", "dossier=' . get_class($this) . 's", false, "' . $this->image . '" );';
		//$return = "jQuery('.addcode').click(function () {";
		//$return .= "jQuery('#code').insertAtCaret(jQuery(this).data('code'));";
		//$return .= "});";
		//$return .='/* Règles de validation */
		//var configvalidate = {
			// ordinary validation config
		//	form : \'#ajaxform\',
			// reference to elements and the validation rules that should be applied
		//	validate : {
		//		\'#label\' : {
		//		    validation : \'length\',
		//			length : \'1-20\'
		//		},
		//		\'#code\' : {
		//			validation : \'length\',
		//			allowing : \'min1\'
		//		},
		//	}
		//};';
		$return ='setTimeout(function() {console.log("refresh");editor.refresh();}, 1000);';
		$return .='jQuery("#BT_modal_image").click(function () {jQuery("#modal_image").modal("show");});';
		$return .='jQuery("#modal_image").on("show.bs.modal", function () {AjaxLoadThumbnails("#modalbody_image", "dossier=' . get_class($this) . 's");});';
	
		return $return;
	}

	public function receive_form($list_data = null) {
		
		list($status, $message) = parent::receive_form(array("label", "image", "asynchrone", "actif", "code"));
		if ($this->asynchrone == "")
			$this->asynchrone = 'off';
		if ($this->actif=="")
			$this->actif = 'off';
/*
    if (isset($_POST["code"]))
       $this->code = $_POST["code"];
		else
       $this->code ="";
*/
    if (preg_match("/\"/", $this->code))
		{
			$status = false;
			$message.= fn_GetTranslation('caracter_prohibited');
		}
    
		if (!fn_CheckSyntax($this->code))
		{
			$status = false;
			$message.= fn_GetTranslation('bad_code') . '<br />' . $this->code;
		}
    
		if (preg_match("!^.*/(.*)$!", $this->image, $regs))
			$this->image = $regs[1];
		return array(
			$status,
			$message
		);
	}
	public function save($list_data = null)
	{
		$to_save = array(
			"label",
			"image",
			"asynchrone",
			"actif"
		);
		if (!isset($this->numero) || $this->numero == "")
			$new = true;
		else
			$new = false;
		$return = parent::save($to_save);
		if (!file_exists("config/" . get_class($this) . "s"))
		{
			mkdir("config/" . get_class($this) . "s");
		}
		if ($handle = @fopen("config/" . get_class($this) . "s/" . $this->numero . ".inc", "w"))
		{
			fwrite($handle, $this->code);
			fclose($handle);
		}
		else
		{
			$return.= fn_GetTranslation('unable_to_write', "config/" . get_class($this) . "s/" . $this->numero . ".inc");
		}
		fn_InitAuthAllUser(get_class($this) , $this->numero);
		return $return;
	}
	public function verif_before_del()
	{
		$doc = new domDocument();
		$doc->preserveWhiteSpace = FALSE;
		$doc->formatOutput = TRUE;
		$doc->load($GLOBALS["config_file"]);
		$xpath = new DomXPath($doc);
		$noeuds = $xpath->query('//crons/cron/scenario[.="' . $this->numero . '"]');
		if ($noeuds->length != 0)
			return false;
		$noeuds = $xpath->query('//btns/btn/pressscenario[.="' . $this->numero . '"]');
		if ($noeuds->length != 0)
			return false;
		$noeuds = $xpath->query('//btns/btn/relachescenario[.="' . $this->numero . '"]');
		if ($noeuds->length != 0)
			return false;
		$noeuds = $xpath->query('//relais/relai/activationscenario[.="' . $this->numero . '"]');
		if ($noeuds->length != 0)
			return false;
		$noeuds = $xpath->query('//relais/relai/deactivationscenario[.="' . $this->numero . '"]');
		if ($noeuds->length != 0)
			return false;
		return true;
	}
	public function disp($commandactive=true, $jsremove=false)
	{
		$classcss='noteditable';
		if ($jsremove)
			$classcss='';
		$return = '<div class="Container animated flipInY ' . $GLOBALS["config"]->csscontainer .' ' .  $classcss.'" data-id="'.$this->config_class.'_'.$this->numero.'">';
		$return.= '   <div class="Entete ' . get_class($this) . '_label_' . $this->numero . '" >' . $this->label . '</div>';
		$return.= '   <img class="Image Pointer ' . get_class($this) . '_image_' . $this->numero . '" id="' . get_class($this) . '_image_' . $this->numero . '" alt="scenario" title="" src="' . $this->image . '" ';
		if ($commandactive==true) {
			//return.= ' onclick="javascript:DisplayMessage(\'' . fn_GetTranslation('run_scenario', $this->label, $this->numero) . '\');';
			//$return.= 'GetXML(\'?app=Ws&amp;page=run&amp;class=' . get_class($this) . '&amp;numero=' . $this->numero . '\', AjaxUpdateMessage, \'BtnRefresh\', true);"';
			$return .= ' onclick="AjaxAction(\'scenario\', \''.$this->numero.'\');"';

		}
		$return.= '/>';
		$return.= '<div class="Caption ' . get_class($this) . '_txt_' . $this->numero . ' Visibility-hidden" id="' . get_class($this) . '_txt_' . $this->numero . '"></div>';
		$return.='<i class="js-remove fa fa-trash-o fa-2x"></i>';	
		$return.= '</div>';
		return $return;
	}
	public function update()
	{
		$this->code = str_replace("wget(", "fn_WgetXml(", $this->code);
		if (preg_match("!.*GetEvent\s*\(.*!", $this->code))
		{
			if (!preg_match("!.*GetEvent\s*\(\s*'google.*!", $this->code) && !preg_match("!.*GetEvent\s*\(\s*'caldav.*!", $this->code))
				$this->code = str_replace("GetEvent(", "GetEvent('caldav', ", $this->code);
		}
		if (preg_match("!.*fn_PurgeTable\s*\(.*!", $this->code))
			array_push($GLOBALS["warning"], fn_GetTranslation('function_updated', 'fn_PurgeTable', $this->numero));
		if (preg_match("!.*fn_MoyennerTable\s*\(.*!", $this->code))
			array_push($GLOBALS["warning"], fn_GetTranslation('function_updated', 'fn_MoyennerTable', $this->numero));
		if (preg_match("!(ipx800_[a-z_]*)!", $this->code, $regs))
			array_push($GLOBALS["warning"], fn_GetTranslation('function_updated', $regs[1], $this->numero));
		if (preg_match("!fn_SetCnt\s*\(.*,.*,.*\)!", $this->code))
			array_push($GLOBALS["warning"], fn_GetTranslation('function_updated', 'fn_SetCnt', $this->numero));
		if (preg_match("!^.*/(.*)$!", $this->image, $regs))
			$this->image = $regs[1];
		if (!isset($this->asynchrone))
			$this->asynchrone = "off";
	}
	
	public function run() {
		$execStatus = 'none';
		if ($this->actif=="on") {
			if ($this->asynchrone == "off") {
				fn_Trace("Execution synchrone scenario " . $this->numero, "scenario");
				$execStatus = eval($this->code);
			}
			else {
				if ($GLOBALS["config"]->general->phppath !== false || $GLOBALS["config"]->general->phppath == "")
				{
					$phpfile = $GLOBALS["config"]->general->phppath;
					$txtexec = $phpfile . " runscenario.php " . $this->numero . " > /dev/null &"; /*." >> trace/".$this->numero.".log 2>&1 &"*/
					fn_Trace("Appel asynchrone scenario " . $this->numero, "scenario");
					$execStatus = exec($txtexec);
					fn_Trace($txtexec, "xml");
				}
				else
				{
					fn_Trace("Execution synchrone scenario " . $this->numero . " car phppath undef", "scenario");
					$execStatus = eval($this->code);
				}
			}
		}
		
		switch ($execStatus) {
			case null:
				$error = "";
				$message = "Ok";
				$status = true;
				break;
			case false:
				$error = "Error";
				$message = "";
				$status = false;				
				break;					
			case "none":
				$error = "scenario inactif";
				$message = "";
				$status = false;				
				break;					
			default:
				$message = $execStatus;
				$error = "";
				$status = true;				
		}
		
		return array($status, $message, $error);
	}
	
	public function mysql_load($when = NULL)
	{
	}
	public function asxml($detail = false)
	{
		if ($detail)
		{
			$return = "<" . get_class($this) . " numero='" . $this->numero . "'>";
			$return.= "<label>" . $this->label . "</label>";
			$return.= "<image>" . $this->image . "</image>";
			$return.= "</" . get_class($this) . ">";
		}
		return $return;
	}
	public function disp_list() {
		$checked="";
		if ($this->actif=="on") $checked="checked";		
		$return  = '<a href="?app=Mn&amp;page=Add&amp;class=' . __class__ . '&amp;numero=' . $this->numero . '&amp;del=true" class="'. $GLOBALS["classDispList"] .'">';
		$return .= '	<div class="well profile_view">';
		$return .= '		<div class="col-sm-12">';
		$return .= '			<h2 class="col-sm-10 left brief searchable filterable" data-beginletter="'.strtoupper(substr($this->label,0,1)).'"><span class="badge bg-red">'.$this->numero.'</span>&nbsp;'.$this->label.'</h2>';
		$return .= '			<p><input type="checkbox" data-toggle="toggle" data-size="small" disabled  data-style="pull-right" '.$checked.' ></p>';
		$return .= '			<div class="left col-xs-8">';
		$return .= '				<p class="searchable">'.ucfirst(fn_GetTranslation("asynchronous")).' : '.$this->asynchrone.'</p>';		
		$return .= '			</div>';
		$return .= '			<div class="right col-xs-4 text-center">';
		$return .= '				<img src="'.$this->image.'" alt="" class="img-circle img-responsive">';
		$return .= '			</div>';
		$return .= '		</div>';
		$return .= '	</div>';
		$return .= '</a>';
		echo $return;
	}	

}
?>