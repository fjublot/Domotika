<?php
	/*----------------------------------------------------------------*
	* Titre : page.php                                              *
	* Classe de page                                                *
	*----------------------------------------------------------------*/
	require_once($GLOBALS["mvc_path"]."top.php");
	class page extends top {
		public $image;
		public $data = array();
		public function __construct($numero="", $info = null) {
			parent::__construct($numero, $info);
			if ( ! isset($this->label) )
				$this->label = 'page perso';
			$this->subclass = array('relai', 'btn', 'cnt', 'an', 'razdevice', 'espdevice', /*'lien',*/ 'scenario', 'variable', 'vartxt', 'sep');
			return $this;
		}
		
		public function del() {
			fn_DelAuth(get_class($this), $this->numero);
			return parent::del();
		}
		
	public function form($page=null) {
			$return  =  fn_HtmlStartPanel(fn_GetTranslation(__class__), $this->label, __class__, $this->numero);
			$return .= '<form name="ajaxform" id="ajaxform" class="form-horizontal form-label-left" novalidate>';
			if (isset($_REQUEST["HTTP_REFERER"]))
				$return .= fn_HtmlHiddenField ('HTTP_REFERER',urlencode($_REQUEST["HTTP_REFERER"]));
				elseif (isset($_SERVER["HTTP_REFERER"]))
					$return .=  fn_HtmlHiddenField('HTTP_REFERER',urlencode($_SERVER["HTTP_REFERER"]));
				else
					$return .=  fn_HtmlHiddenField('HTTP_REFERER', '');
			$return .=  fn_HtmlHiddenField('class',__class__);
			if (isset($this->numero))
				$return .=  fn_HtmlHiddenField('numero',$this->numero);
			$return .= fn_HtmlHiddenField('action','');
			$return .= fn_HtmlHiddenField('UpdateStatus',"0");
			$return .= fn_HtmlHiddenField('data',join(',', $this->data));
			$return .= fn_HtmlInputField('label', $this->label, 'text', 'page_name', 'page.label', '');
			//$return .= fn_HtmlSelectField('image', 'image', 'page.image',"UpdateImage(this.id);",false,true);
			
			$return .= '<div id="SelectableItems" style="margin-top: 60px;">';
			$return .= '<ul id="class" class="nav nav-tabs bar_tabs" role="tablist">';
			$first = "active";
			foreach ($this->subclass as $class) {
				$return .= '<li role="presentation" class="'.$first.'">';
				$return .= ' <a href="#'.$class.'" id="tab-'.$class.'" role="tab" data-toggle="tab" aria-expanded="true">'.ucfirst(fn_GetTranslation($class.'s')).'</a>';
				$return .= '</li>';
				$first="";
			}
			$return .= '</ul>';
			$return .= '<div id="ongletsContent" class="tab-content">';
			$first = "active";
			foreach ($this->subclass as $class) {
				if ( isset($GLOBALS["config"]->{$class."s"}) ) {
					$return .= '<div id="'.$class.'" class="tab-pane fade in '.$first.'" aria-labelledby="'.fn_GetTranslation($class).'" role="tabpanel">';
					$first = "";
					foreach($GLOBALS["config"]->{$class."s"}->{$class} as $info) {
						$current = new $class($info->attributes()->numero, $info);
						$return .= '<!-- '.$class.'('.$info->attributes()->numero.') -->'.PHP_EOL;
						$return .= $current->disp(false,false).PHP_EOL;
					}
					$return .= '</div>';
				}
			}
			$return .= '</div><!-- /ongletsContent -->';
			$return .= '</div><!-- /SelectableItems -->';
			$return .= '<div class="clearfix"/>';
			$return .= '<div id="SelectedItems" style="margin-top: 60px;">';
			$return .= '<ul id="class" class="nav nav-tabs bar_tabs" role="tablist">';
			$return .= '<li role="presentation" class="active">';
			$return .= ' <a href="#page_"'.$this->numero.'" id="tab-currentpage" role="tab" data-toggle="tab" aria-expanded="true">'.$this->label.'</a>';
			$return .= '</li>';
			$return .= '</ul>';			
			$return .= '<div id="selection" class="selecteditems editable">';
			foreach($this->data as $item) {
				list($class, $numero) = explode("_", $item);
				$current = new $class($numero);
				$return .= $current->disp(false,true);
			}
			$return .= '</div><!-- /selection -->';
			$return .= '</div><!-- /SelectedItems -->';
			return $return;
		}
		public function js()
		{
			$return  = 'AjaxLoadSelectJson("image", "dossier='.get_class($this).'s", false, "'.$this->image.'" );'.PHP_EOL;
			$return .= 'UpdateStatus();';
			$return .= '(function () {
		\'use strict\';

		var byId = function (id) { return document.getElementById(id); },

			loadScripts = function (desc, callback) {
				var deps = [], key, idx = 0;

				for (key in desc) {
					deps.push(key);
				}

				(function _next() {
					var pid,
						name = deps[idx],
						script = document.createElement(\'script\');

					script.type = \'text/javascript\';
					script.src = desc[deps[idx]];

					pid = setInterval(function () {
						if (window[name]) {
							clearTimeout(pid);

							deps[idx++] = window[name];

							if (deps[idx]) {
								_next();
							} 
							else {
								callback.apply(null, deps);
								}
						}
					}, 30);

					document.getElementsByTagName(\'head\')[0].appendChild(script);
				})()
			},

			console = window.console;


		if (!console.log) {
			console.log = function () {
				alert([].join.apply(arguments, \' \'));
			};
		}';
		$return .='[';
		
			foreach ($this->subclass as $class)
			{
				if ( isset($GLOBALS["config"]->{$class."s"}) )
				{
					$return .= '{';
					$return .= 'id:\''.$class.'\',';
					$return .= 'name:\'sortablediv\',';
					$return .= 'pull:\'clone\',';
					$return .= 'put:false,';
					$return .= '},';
				}
			}
		$return .='	{
			id: \'selection\',
			name: \'sortablediv\',
			pull: false,
			put: true
		}].forEach(function (groupOpts, i) {
			console.log(\'Sortable.create(\'+groupOpts["id"]+\')\');
			Sortable.create(byId(groupOpts["id"]), {
				//sort: (i != 1),
				group: groupOpts,
				animation: 150,
				filter: \'.js-remove\',
				onFilter: function (evt) {
					evt.item.parentNode.removeChild(evt.item);
					//saveDragDrop
					var saveString = "";
					var mainContainer = document.getElementById(\'selection\');
					var divs = mainContainer.getElementsByClassName(\'Container\');
					for(var no=0;no<divs.length;no++){	// LOoping through all <div>
						if(saveString.length>0) saveString = saveString + ",";
						saveString = saveString + divs[no].getAttribute("data-id");
					}
					document.getElementById(\'data\').value = saveString;

				}
			});
		});

	})();';

		//$return .='jQuery(".ContainersList").hide();' ;
		$return .='jQuery("#selection").show();' ;
	/*	
		$return .='jQuery(".Accordeon").html("[+] Afficher plus de détails");'; 
				 
		
		$return .='jQuery(".Accordeon").click('; 
		$return .='    function() {';
		$return .='        if( jQuery(this).text().indexOf("[-]") > -1 ) {';
		$return .='            jQuery(this).html("[+] Afficher plus de détails");'; 
		$return .='        	   jQuery(this).parent().next("div").slideToggle("slow");'; 
		$return .='        	   }'; 
		$return .='        else {';
		$return .='            jQuery(this).html("[-] Réduire");'; 
		$return .='        	   jQuery(this).parent().next("div").slideToggle("slow");';
		$return .='        	   }'; 
		$return .='        return false;'; 
		$return .='});'; 
	*/	
			return $return;
		}
		public function receive_form($list_data = null)	{
			if (isset($_POST["data"]))
				$this->data = explode(',', $_POST["data"]);
			list($status, $message) = parent::receive_form(array("label", "image"));
			return array($status, $message);
		}
		public function save($list_data = null) {
			if ( ! isset($this->numero) || $this->numero == "" )
				$new = true;
			else
				$new = false;
			$old_data = $this->data;
			$this->data = array();
			if ($old_data[0]!="") { // Cas de la page vide
				foreach ($old_data as $item) {
					list($class, $numero) = explode("_", $item);
					if ( in_array($class, $this->subclass) ) {
						array_push($this->data, $item);
					}
				}
			}
			$return = parent::save(array("label", "data", "image"));
			fn_InitAuthAllUser(get_class($this), $this->numero);
			return $return;
		}
		public function verif_before_del() {
			return true;
		}
		public function update() {
		}
		public function asxml($detail = false) {
			$return = "<".get_class($this)." numero='".$this->numero."'>";
			if ( strlen($this->image) == 0 ) {
				$this->image = "#N/A";
			}
			$return .= "<image>".$this->image."</image>" ;
			$return .= "<label>".$this->label."</label>";
			if ( $detail ) {
				$return .= "<data>".join(',', $this->data)."</data>";
			}
			$return .= "</".get_class($this).">";
			return $return;
		}
		public function disp_li($first=null) {
			$classcss='';
			if ($first) {
				$classcss = 'class="ui-btn-active"';
			}
			//return '<li><a href="#tab'.$this->numero.'" '.$classcss.' data-tab-class="tab'.$this->numero.'">'.$this->label.'</a></li>'.PHP_EOL;
		  return '<li><a href="#page'.$this->numero.'" data-role="page" data-icon="grid" '.$classcss.'>'.$this->label.'</a></li>'.PHP_EOL;
		}
		public function disp_list() {
			$return  = '<a href="?app=Mn&page=Add&class='.__class__.'&numero='.$this->numero.'" class="col-md-3 col-sm-3 col-xs-12 animated fadeInDown btn-app">';
			$return .= '	<div class="well profile_view">';
			$return .= '		<div class="col-sm-12">';
			$return .= '			<h2 class="col-sm-10 left brief searchable filterable" data-beginletter="'.strtoupper(substr($this->label,0,1)).'"><span class="badge bg-red">'.$this->numero.'</span>&nbsp;'.$this->label.'</h2>';
			$return .= '			<div class="left col-xs-8">';
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