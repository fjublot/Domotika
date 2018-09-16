<?php
/*----------------------------------------------------------------*
* Titre : graphique.php                                            *
* Classe de graphique                                              *
*----------------------------------------------------------------*/
require_once($GLOBALS["mvc_path"]."top.php");
class graphique extends top
{
	public $type, $mode, $title, $subtitle, $public, $height, $width, $export, $decimalPoint, $thousandsSep, $navigator, $dateformat, $picto;
	public $data = array();
	public $step_serie = array();
	public $type_serie = array();
	public $chart_option, $colors_option, $credits_option, $exporting_option, $labels_option, $legend_option, $loading_option, $navigation_option, $navigator_option, $plotOptions_option, $rangeSelector_option, $scrollbar_option, $series_option, $subtitle_option, $title_option, $tooltip_option, $pane_option, $xAxis_option, $yAxis_option;
	public function __construct($numero="", $info = null) {
		parent::__construct($numero, $info);
		if ( ! isset($this->label) ) {
			$this->label = 'graphique perso';
			$this->decimalPoint = ',';
			$this->thousandsSep = ' ';
			$this->title = 'graphique perso';
			$this->subtitle = 'graphique perso';
			$this->public = false;
			$this->height = 500;
			$this->width = 500;
			$this->export = false;
			$this->navigator = 0;
			$this->dateformat = '%d/%m/%Y %H:%M:%S';
		}
		if ( ! isset($this->picto) )
			$this->picto = "images/".__class__."s/".__class__.".jpg";
		return $this;
	}
	public function del() {
		fn_DelAuth(get_class($this), $this->numero);
		return parent::del();
	}
	public function form($page=null) {
		$return  =  fn_HtmlStartPanel($this->label, fn_GetTranslation(__class__), __class__, $this->numero);
		$return .= '<form name="ajaxform" id="ajaxform" class="form-horizontal form-label-left">';
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
  
		$return .= fn_HtmlInputField('label', $this->label, 'text', 'graphique_name', 'graphique.label', '');
		$return .= fn_HtmlInputField('title', $this->title, 'text', 'title', 'graphique.title', '');
		$return .= fn_HtmlInputField('subtitle', $this->subtitle, 'text', 'subtitle', 'graphique.subtitle', '');
 		$return .= fn_HtmlSelectField('mode', 'mode', 'graphique.mode',"ChangeModeGraph()",false,false);
 		$return .= fn_HtmlSelectField('type', 'type', 'graphique.type',"",false,false);
		$return .= fn_HtmlBinarySelectField('public', $this->public, 'public', 'graphique.public');
 		$return .= fn_HtmlSelectField('data', 'data', 'graphique.data',"",true,false);
		$return .= fn_HtmlInputField('height', $this->height, 'text', 'height', 'graphique.height', '');
		$return .= fn_HtmlInputField('width', $this->width, 'text', 'width', 'graphique.width', '');
		$return .= fn_HtmlInputField('decimalPoint', $this->decimalPoint, 'text', 'decimalPoint', 'graphique.decimalPoint', '');
		$return .= fn_HtmlInputField('thousandsSep', $this->thousandsSep, 'text', 'thousandsSep', 'graphique.thousandsSep', '');
		$return .= fn_HtmlInputField('dateformat', $this->dateformat, 'text', 'dateformat', 'graphique.dateformat', '');
		$return .= fn_HtmlBinarySelectField('export', $this->export, 'export', 'graphique.export');
		$return .= fn_HtmlInputField('navigator', $this->navigator, 'text', 'navigator', 'graphique.navigator', '');
		for($id_serie=0; $id_serie < count($this->data); $id_serie++) {
			$return .= fn_HtmlStartFieldset(ucfirst(fn_GetTranslation('advance_option_series').' '.($id_serie+1)), 'advance_option_series'.$id_serie);
			$return .= fn_HtmlBinarySelectField("step_serie[".$id_serie."]", $this->step_serie[$id_serie], 'step_option', 'graphique.step_serie');
			$return .= fn_HtmlSelectField("type_serie[".$id_serie."]", 'type', 'graphique.type',"",false,false);
			$return .= fn_HtmlEndFieldset();
		}
		$return .= fn_HtmlStartFieldset(fn_GetTranslation('advance_option'), 'advance_option', true);
		foreach (array(
			'chart', 
			'colors', 
			'credits', 
			'exporting', 
			'labels', 
			'legend', 
			'loading', 
			'navigation', 
			'navigator', 
			'plotOptions', 
			'rangeSelector', 
			'scrollbar', 
			'series', 
			'subtitle', 
			'title', 
			'tooltip', 
			'pane', 
			'xAxis', 
			'yAxis') as $item) {
			$return .= fn_HtmlTextAreaField($item.'_option', $this->{$item.'_option'}, 'text', $item.'_option', 'graphique.chart_option', false) ;
		}
		$return .= fn_HtmlEndFieldset();

		return $return;
	}
	public function js()
	{
		$return  = 'AjaxLoadSelectJson("mode", "class=graphmode", false, "'.$this->mode.'" );';
		$return .= 'AjaxLoadSelectJson("type", "class=courbe", false, "'.$this->type.'" );';
        $return .= 'AjaxLoadSelectJson("data", "class=an|relai|btn|variable|cnt&prefix=true", false, "'.join('", "', $this->data).'" );';
		
		for($id_serie=0; $id_serie < count($this->data); $id_serie++) {
			$return .= 'AjaxLoadSelectJson("type_serie['.$id_serie.']", "class=courbe", false, "'.$this->type_serie[$id_serie].'" );';
			//$return .= '$("#advance_option_series_'.$id_serie.'").hide();';
			//$return .= '$("#toggle_advance_option_series_'.$id_serie.'").click(function () {$("#advance_option_series_'.$id_serie.'").slideToggle("slow");$(this).toggleClass("toggleafficher"); return false;});';
		}
		//$return .= '$("#advance_option").hide();';
		//$return .= '$("#toggle_advance_option").click(function () {$("#advance_option").slideToggle("slow");$(this).toggleClass("toogleafficher"); return false;});';
		return $return;
	}
	public function receive_form($list_data = null)
	{
		list($status, $message) = parent::receive_form(array("label", "type", "mode", "title", "subtitle", "data", "height", "width", "decimalPoint", "thousandsSep", "navigator", "chart_option", "colors_option", "credits_option", "exporting_option", "labels_option", "legend_option", "loading_option", "navigation_option", "navigator_option", "plotOptions_option", "rangeSelector_option", "scrollbar_option", "series_option", "subtitle_option", "title_option", "tooltip_option", "pane_option", "xAxis_option", "yAxis_option", "dateformat", "export", "public", "picto"));
		$this->step_serie = array();
		$this->type_serie = array();
		for($id_serie=0; $id_serie < count($this->data); $id_serie++) {
			if ( isset($_REQUEST["step_serie"][$id_serie]) )
				$this->step_serie[$id_serie] = $_REQUEST["step_serie"][$id_serie];
			if ( isset($_REQUEST["type_serie"][$id_serie]) )
				$this->type_serie[$id_serie] = $_REQUEST["type_serie"][$id_serie];
			else
				$this->type_serie[$id_serie] = $this->type;
		}
		if ( $this->public=="")
			$this->public="off";
		if ( $this->export=="")
			$this->export="off";


		return array($status, $message);
	}
	public function save($list_data = null)
	{
		if ( ! isset($this->numero) || $this->numero == "" )
			$new = true;
		else
			$new = false;
		for($id_serie=0; $id_serie < count($this->data); $id_serie++)
		{
			list($data_class, $data_numero) = explode("_", $this->data[$id_serie]);
			if ( ! isset($this->type_serie[$id_serie]) )
				$this->type_serie[$id_serie] = $this->type;
			if ( ! isset($this->step_serie[$id_serie]) )
			{
				if ( in_array ($data_class, array("relai", "btn")) )
				{
					$this->step_serie[$id_serie] = "true";
					$this->type_serie[$id_serie] = "line";
				}
				else
					$this->step_serie[$id_serie] = "false";
			}
		}
		$return = parent::save(array("label", "type", "mode", "title", "export", "public", "subtitle", "data", "height", "width", "decimalPoint", "thousandsSep", "navigator", "chart_option", "colors_option", "credits_option", "exporting_option", "labels_option", "legend_option", "loading_option", "navigation_option", "navigator_option", "plotOptions_option", "rangeSelector_option", "scrollbar_option", "series_option", "subtitle_option", "title_option", "tooltip_option", "pane_option", "xAxis_option", "yAxis_option", "type_serie", "step_serie", "dateformat", "picto"));
		fn_InitAuthAllUser(get_class($this), $this->numero);
		return $return;
	}
	public function verif_before_del()
	{
		return true;
	}
	public function update()
	{
		if ( !isset($this->dateformat) )
			$this->dateformat = '%d/%m/%Y %H:%M:%S';
		for($id_serie=0; $id_serie < count($this->data); $id_serie++)
		{
			if ( ! isset($this->type_serie[$id_serie]) )
				$this->type_serie[$id_serie] = $this->type;
		}
	}
	public function dispGraph() { 
		if ( $this->mode == "Chart" )
			$repjs = "highcharts";
		else
			$repjs = "highstock";
		$titre = addcslashes(html_entity_decode($this->label), "'");
		// Prépare les axes
		$yaxis = array();
		$yaxisid = array();
		$series = array();
		$count = 0;
		$countyaxis = 0;
		$yaxiscolor = array("#89A54E", "#4572A7", "#AA4643", "#B02FCD", "#B02FCD", "#17AE28", "#D78915");
		foreach ($this->data as $item) {
			list($class, $numero) = explode("_", $item);
			$current_data = new $class($numero);
			$unite ="";
			if ( isset($current_data->type) ) {
				$xpathModele = '//type'.get_class($current_data).'s/type'.get_class($current_data).'[@numero="'.$current_data->type.'"]';
				$type = $GLOBALS["config"]->xpath($xpathModele);
				if ( isset($type[0]->{'unit'}) )
					$unite = (string)$type[0]->{'unit'};
			}
			if ( isset($current_data->unite) )
				$unite = (string)$current_data->unite;
			if ( isset($unite) ) {
				if ( ! array_key_exists($unite, $yaxis) ) {
					$xpathModele='//unites/unite[@numero="'.$unite.'"]';
					$unite_info = $GLOBALS["config"]->xpath($xpathModele);
					if ( $unite_info != false ) {
						$yaxis[$unite] = "{
							labels: {
								formatter: function() {
									return this.value +' ".addcslashes($unite_info[0]->{'label'}, "'")."';
								},
								style: {color: '".$yaxiscolor[$countyaxis]."'},
								x: -8,
								align: 'right'
							},
							title: {
								text: '".addcslashes($unite_info[0]->{'type'}, "'")."',
								style: {color: '".$yaxiscolor[$countyaxis]."'}
							},
						}";
						$yaxisid[$unite] = $countyaxis;
						$countyaxis++;
					} else {
						$yaxis[$unite] = "{
							labels: {
								formatter: function() {return this.value;},
								style: {color: '".$yaxiscolor[$countyaxis]."'},
								x: -8,
								align: 'right'
							},
							title: {
								text: 'Sans unité',
								style: {color: '".$yaxiscolor[$countyaxis]."'}
							}
						}";
						$yaxisid[$unite] = $countyaxis;
						$countyaxis++;
					}
				}
			}
			$seriesunite[$count] = $yaxisid[$unite];
		  $count ++;
		}
		echo '<script type="text/javascript">';
	?>
	(function($){ // encapsulate jQuery
		$(function() {
			var seriesOptions = [],
			yAxisOptions = [],
			seriesCounter = 0;
			function createChart() {
				Highcharts.<?php echo $this->mode; ?>({
					chart: {
						renderTo: 'container_<?php echo $this->numero; ?>',
						zoomType: 'x',
						resetZoomButton: {
							position: {
								// align: 'right', // by default
								// verticalAlign: 'top', // by default
								x: 0,
								y: -30
							}
						},
						/*width: '<?php echo $this->width; ?>',*/
						height: <?php echo $this->height; ?>
						<?php
							if ( $this->chart_option != "" )
								echo ",\n".$this->chart_option;
						?>
		        	},
					events: {
						load: function(event) {
							event.target.reflow();
						}
					},
					global : {
						useUTC : false
					},
					title: {
						text: '<?php echo addcslashes ($this->title, "'"); ?>'
					},
					subtitle: {
						text: '<?php echo addcslashes ($this->subtitle, "'"); ?>'
					},
					<?php
					foreach (array('colors', 'labels', 'loading', 'navigation', 'plotOptions', 'rangeSelector', 'scrollbar') as $item)
					{
						if ( $this->{$item.'_option'} != "" )
							echo $item.": {\n".$this->{$item.'_option'}."\n},\n";
					}
					foreach (array('credits', 'legend') as $item)
					{
						if ( $this->{$item.'_option'} != "" )
							echo $item.": {\n".$this->{$item.'_option'}."\n},\n";
						else
							echo $item.": {\nenabled: false\n},\n";
					}
					echo "tooltip : {
		            xDateFormat: '".$this->dateformat."',
		            shared: true";
						if ( $this->tooltip_option != "" )
							echo ",\n".$this->tooltip_option;
					echo "},";
					?>
					navigator : {
						adaptToUpdatedData: false,
						<?php
						if ( $this->{'navigator'} != "" )
							echo "series : {
							baseSeries : ".$this->{'navigator'}."
						}";
						if ( $this->{'navigator_option'} != "" )
							echo ",\n".$this->{'navigator_option'};
						?>
					},
					exporting: {
						enabled: <?php if ($this->export == 'on') echo 1; else echo 0; ?><?php
		            if ( $this->exporting_option != "" )
		            	echo ",\n".$this->exporting_option;
		            ?>
					},
					lang: {
						months: [
							'<?php echo fn_GetTranslation("mois_1"); ?>',
							'<?php echo fn_GetTranslation("mois_2"); ?>',
							'<?php echo fn_GetTranslation("mois_3"); ?>',
							'<?php echo fn_GetTranslation("mois_4"); ?>',
							'<?php echo fn_GetTranslation("mois_5"); ?>',
							'<?php echo fn_GetTranslation("mois_6"); ?>',
							'<?php echo fn_GetTranslation("mois_7"); ?>',
							'<?php echo fn_GetTranslation("mois_8"); ?>',
							'<?php echo fn_GetTranslation("mois_9"); ?>',
							'<?php echo fn_GetTranslation("mois_10"); ?>',
							'<?php echo fn_GetTranslation("mois_11"); ?>',
							'<?php echo fn_GetTranslation("mois_12"); ?>'
						],
						weekdays: [
							'<?php echo fn_GetTranslation("jour_0"); ?>',
							'<?php echo fn_GetTranslation("jour_1"); ?>',
							'<?php echo fn_GetTranslation("jour_2"); ?>',
							'<?php echo fn_GetTranslation("jour_3"); ?>',
							'<?php echo fn_GetTranslation("jour_4"); ?>',
							'<?php echo fn_GetTranslation("jour_5"); ?>',
							'<?php echo fn_GetTranslation("jour_6"); ?>'
						],
						downloadJPEG: '<?php echo addcslashes (fn_GetTranslation("downloadJPEG"), "'"); ?>',
						downloadPDF: '<?php echo addcslashes (fn_GetTranslation("downloadPDF"), "'"); ?>',
						downloadPNG: '<?php echo addcslashes (fn_GetTranslation("downloadPNG"), "'"); ?>',
						downloadSVG: '<?php echo addcslashes (fn_GetTranslation("downloadSVG"), "'"); ?>',
						exportButtonTitle: '<?php echo addcslashes (fn_GetTranslation("exportButtonTitle"), "'"); ?>',
						loading: '<?php echo addcslashes (fn_GetTranslation("loading"), "'"); ?>',
						printButtonTitle: '<?php echo addcslashes (fn_GetTranslation("printButtonTitle"), "'"); ?>',
						rangeSelectorFrom: '<?php echo addcslashes (fn_GetTranslation("rangeSelectorFrom"), "'"); ?>',
						rangeSelectorTo: '<?php echo addcslashes (fn_GetTranslation("rangeSelectorTo"), "'"); ?>',
						rangeSelectorZoom: '<?php echo addcslashes (fn_GetTranslation("rangeSelectorZoom"), "'"); ?>',
						resetZoom: '<?php echo addcslashes (fn_GetTranslation("resetZoom"), "'"); ?>',
						resetZoomTitle: '<?php echo addcslashes (fn_GetTranslation("resetZoomTitle"), "'"); ?>',
						decimalPoint: '<?php echo addcslashes ($this->decimalPoint, "'"); ?>',
						thousandsSep: '<?php echo addcslashes ($this->thousandsSep, "'"); ?>'
					},
					xAxis: {
						minPadding: 0,
						maxPadding: 0,
					<?php
					if ( $this->mode == "Chart" )
						echo "type: 'datetime',";
					else
						echo "ordinal: false,";
					?>
						minRange: 3600 * 1000 // one hour
					},
	            yAxis: [<?php echo implode(",", $yaxis); ?>],
	            series: seriesOptions
				});
			}
		<?php
			$count = 0;
			foreach ($this->data as $item) {
				list($class, $numero) = explode("_", $item);
				$current_data = new $class($numero);
				echo "$.getJSON('?app=Ws&page=graphdata&class=".$class."&numero=".$numero."&callback=?', function(data) {
					seriesOptions[".$count."] = {
						name: '".addcslashes ($current_data->label, "'")."',
						data: data,
						type: '".$this->type_serie[$count]."',
						step: ".$this->step_serie[$count].",
						yAxis: ".$seriesunite[$count]."
					};
					seriesCounter++;
					if (seriesCounter == ".count($this->data).") {
						createChart();
					}
				});\n";
				$count++;
			}
				
		?>
	
		
		});
		
	
	})(jQuery);
    <?php
    echo '</script>';
 		echo '<script type="text/javascript" src="ressources/'.$repjs.'/'.$repjs.'.js"></script>'.PHP_EOL;
		//echo '<script type="text/javascript" src="ressources/'.$repjs.'/highcharts-more.js"></script>'.PHP_EOL;
		echo '<script type="text/javascript" src="ressources/'.$repjs.'/modules/exporting.js"></script>'.PHP_EOL;
	}
	public function disp_list() {
		//$beginletter="", $searchstr=""
		//if ((($beginletter=='') || ($beginletter == substr(strtoupper($this->label),0,1))) && 
		//	(($searchstr=='') || (stristr($this->label, $searchstr) != false || stristr($this->mail, $searchstr) != false)))			 {
				$checked="";
				//if ($this->actif=="on") $checked="checked";
				$return  = '<a href="?app=Mn&page=Add&class='.__class__.'&numero='.$this->numero.'" class="'. $GLOBALS["classDispList"] .' btn-app">';
				$return .= '	<div class="well profile_view">';
				$return .= '		<div class="col-sm-12">';
				$return .= '			<h2 class="col-sm-10 left brief searchable filterable" data-beginletter="'.strtoupper(substr($this->label,0,1)).'"><span class="badge bg-red">'.$this->numero.'</span>&nbsp;'.$this->label.'</h2>';
				//$return .= '			<p><input type="checkbox" data-toggle="toggle" data-size="small" disabled  data-style="pull-right" '.$checked.' ></p>';
				$return .= '			<div class="left col-xs-8">';
				//$return .= '				<p class="searchable">'.$this->telmobile.'</p>';
				$return .= '			</div>';
				$return .= '			<div class="right col-xs-4 text-center">';
				//$return .= '				<img src="'.$this->picto.'" alt="" class="img-circle img-responsive">';
				$return .= '			</div>';
				$return .= '		</div>';
				$return .= '	</div>';
				$return .= '</a>';
				echo $return;
			//}
	}
	
  
}
?>