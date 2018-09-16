<?php
/*----------------------------------------------------------------*
* Titre : camera.php                                              *
* Classe de camera                                                *
*----------------------------------------------------------------*/
require_once($GLOBALS["mvc_path"]."top.php");
class camera extends top
{
	public $host, $port, $user, $password, $type, $canal, $width, $height, $quality, $flux, $image, $option, $autorefresh, $pushscenario, $timezone, $active;
	public $cderelai = array();
	public function __construct($numero="", $info = null)
	{
		parent::__construct($numero, $info);
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
		$return .= '		<div class="x_content">';
		$return .= '			<form name="ajaxform" id="ajaxform" class="form-horizontal form-label-left" novalidate>';
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
			$return .=  fn_HtmlHiddenField('action','');
		$return .= fn_HtmlInputField('label', $this->label, 'text', 'camera_label', 'camera.label', '');
		$return .= fn_HtmlBinarySelectField('active', $this->active, 'camera_active', 'camera.active', true);
		$return .= fn_HtmlInputField('host', $this->host, 'url', 'camera_hostname', 'camera.host', "");
		$return .= fn_HtmlInputField('port', $this->port, 'text', 'camera_port', 'camera.port', "");
 		$return .= fn_HtmlSelectField('type', 'camera_type', 'camera.type',"");
		//GetXML('?app=Ws&page=getcamfile&numero='+this.options[this.options.selectedIndex].value, AjaxUpdateCam, 'BtnRefresh')
		$return .= fn_HtmlInputField('user', $this->user, 'text', 'camera_user', 'camera.user', '');
		$return .= fn_HtmlInputField('password', $this->password, 'text', 'camera_password', 'camera.password', '');
		$return .= fn_HtmlInputField('canal', $this->canal, 'text', 'camera_canal', 'camera.canal', '');
		$return .= fn_HtmlInputField('width', $this->width, 'text', 'camera_width', 'camera.width', '');
		$return .= fn_HtmlInputField('height', $this->height, 'text', 'camera_height', 'camera.height', '');
		$return .= fn_HtmlInputField('quality', $this->quality, 'text', 'camera_quality', 'camera.quality', '');
		$return .= fn_HtmlInputField('flux', $this->flux, 'text', 'camera_flux', 'camera.flux', '');
		$return .= fn_HtmlInputField('image', $this->image, 'text', 'camera_image', 'camera.image', '');
		$return .= fn_HtmlInputField('option', $this->option, 'text', 'camera_option', 'camera.option', '');
		$return .= fn_HtmlBinarySelectField('autorefresh', $this->autorefresh, 'camera_autorefresh', 'camera.autorefresh');
 		$return .= fn_HtmlSelectField('pushscenario', 'camera_pushscenario', 'camera.pushscenario','');
 		$return .= fn_HtmlSelectField('timezone', 'camera_timezone', 'camera.timezone','');
		//$return .= '<fieldset>
		// 		        <legend>&nbsp;'.fn_GetTranslation('commande').'&nbsp;</legend>
		// 		        <SELECT name="cderelai[]" id="cderelai" size="5" multiple></select>';
		//$return .= fn_Help('camera.cderelai');
		//$return .= '</fieldset>';
		return $return;
	}
	public function js()
	{
		$return='
     		AjaxLoadSelectJson("type", "class=typecamera", false, "'.$this->type.'" );
    		//AjaxLoadSelectJson("cderelai", "class=Relai", true, "'.join('", "', $this->cderelai).'" );
     		AjaxLoadSelectJson("pushscenario", "class=scenario", true, "'.$this->pushscenario.'" );
    		AjaxLoadSelectJson("timezone", "class=timezone", false, "'.$this->timezone.'" );
    		//GetXML("?app=Ws&page=getcamfile&numero='.$this->type.'", AjaxUpdateCam, "BtnRefresh");
		';
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
					\'#port\' : {
						validation : \'number\',
						allowing : \'range[1;16385]\'
					}
				}
			};
		';
		
		return $return;
	}
  	public function receive_form($list_data = null)
	{
		list($status, $message) = parent::receive_form(array("label", "host", "port", "canal", "user", "password", "type",  "width", "height", "quality", "flux", "image", "option", "autorefresh", "cderelai", "pushscenario", "timezone", "active"));
		if ($this->active == "")
			$this->active = 'off';
		return array($status, $message);
	}
	public function save($list_data = null)
	{
		if ( ! isset($this->numero) || $this->numero == "" )
			$new = true;
		else
			$new = false;
		$retour = parent::save(array("label", "host", "port", "canal", "user", "password", "type",  "width", "height", "quality", "flux", "image", "option", "autorefresh", "cderelai", "pushscenario", "timezone", "active"));
		fn_InitAuthAllUser("camera", $this->numero);
		return $retour;
	}
	public function verif_before_del()
	{
		return true;
	}
  
	public function disp()
	{
  		return true;
		}

	public function streamview() {
		?>
		function streamview<?php echo $this->numero;?> () {
			jQuery.ajax({
					type:      'GET',
					url:        '?app=Ws&page=viewcamproxy&numero=<?php echo $this->numero;?>',
					async: false,
					cache:   false,
					contentType: "image/jpg",
					success: function(data) {
						$src = "data:image/jpeg;base64," + data;
						jQuery("#livestream<?php echo $this->numero; ?>").attr("src", $src);
					}
			});
		}
		
<?php }

	public function updatestream() {
		?>
		$(function() {
		var timeout = 500;
		var refreshInterval = setInterval(function() {var videolive = streamview<?php echo $this->numero;?>();}, timeout);
		});
	<?php
	}
	/*
	public function streamview() {
		?>
		$(function() {
		var timeout = 2000;
		var refreshInterval = setInterval(function() {
			var random = Math.floor(Math.random() * Math.pow(2, 31));
			$('#livestream<?php echo $this->numero;?>').attr('src', '?app=ws&page=viewcamproxy&numero=<?php echo $this->numero;?>&random=' + random);
    }, timeout);
 })
	}
 */
		
	public function disp_list() {
		$checked="";
		if ($this->active=="on") $checked="checked";
		$xpath  = '//typecameras/typecamera[@numero="'.$this->type.'"]';
		$labeltype = fn_GetByXpath($xpath, 'bal', 'label');
		$return  = '<a href="?app=Mn&amp;page=Add&amp;class=' . __class__ . '&amp;numero=' . $this->numero . '&amp;del=true" class="'. $GLOBALS["classDispList"] .'">';
		$return .= '	<div class="well profile_view">';
		$return .= '		<div class="col-sm-12">';
		$return .= '			<h2 class="col-sm-10 left brief searchable filterable" data-beginletter="'.strtoupper(substr($this->label,0,1)).'"><span class="badge bg-red">'.$this->numero.'</span>&nbsp;'.$this->label.'</h2>';
		$return .= '			<p><input type="checkbox" data-toggle="toggle" data-size="small" disabled  data-style="pull-right" '.$checked.' ></p>';
		$return .= '			<div class="left col-xs-12">';
		$return .= '				<p class="searchable">'.fn_GetTranslation("camera_type").' : '.$labeltype.' ('.$this->type.')</p>';
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