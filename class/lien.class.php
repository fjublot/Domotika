<?php
/*----------------------------------------------------------------*
* Titre : lien.php                                               *
* Classe de lien                                                 *
*----------------------------------------------------------------*/
require_once($GLOBALS["mvc_path"]."top.php");
class lien extends top
{
	public $href, $image ;
	public function __construct($numero, $info = null)
	{
		parent::__construct($numero, $info);
		if ( ! isset($this->href) )
		{
			$this->href = "http://";
			$this->image = $GLOBALS["root_path"].'images/'.get_class($this)."s/vide.png";
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
		$return  =  fn_HtmlStartPanel(fn_GetTranslation(__class__), $this->label, __class__, $this->numero);
		$return .= '<form name="ajaxform" id="ajaxform" class="form-horizontal form-label-left">';
		if (isset($_REQUEST["HTTP_REFERER"]))
			$return .= '			<input type=hidden name=HTTP_REFERER value="' . urlencode($_REQUEST["HTTP_REFERER"]) . '">';
  		elseif (isset($_SERVER["HTTP_REFERER"]))
  			$return .=  '			<input type=hidden name=HTTP_REFERER value="' . urlencode($_SERVER["HTTP_REFERER"]) . '">';
  		else
  			$return .=  '			<input type=hidden name=HTTP_REFERER value="">';

		$return .=  '			<input type=hidden name=class value="' . __class__ . '">';
        if (isset($this->numero))
  			$return .=  '			<input type=hidden name="numero" value="' . $this->numero . '">';
		$return .= fn_HtmlHiddenField('action','action');		
		$return .= fn_HtmlInputField('label', $this->label, 'text', 'lien_label', 'lien.label', '',true);
		$return .= fn_HtmlInputField('href', $this->href, 'text', 'lien_href', 'lien.href', '',true);
		$return .= fn_HtmlButtonPicto('image', $this->image, 'image', 'lien.image');
		$return .= '<script src="ressources/previewimage/jquery.previewimage.js"></script>';
		return $return;
	}
	public function js()
	{
		$return  ='AjaxLoadSelectJson("#image", "dossier='.get_class($this).'s", false, "'.$this->image.'" );';
		$return .='jQuery(\'.previewimage\').preimage();';
				// Pour afficher la modal sur le click du bouton
		$return .='jQuery("#BT_modal_image").click(function () {jQuery("#modal_image").modal("show");});';
		// Pour charger le contenu de la modal lors de son chargement	
		$return .='jQuery("#modal_image").on("show.bs.modal", function () {AjaxLoadThumbnails("#modalbody_image", "dossier=' . get_class($this) . 's");});';

		return $return;
	}
	public function receive_form($list_data = null)
	{
		list($status, $message) = parent::receive_form(array("label", "href", "image"));
		return array($status, $message);
	}
	public function save($list_data = null)
	{
		$return = parent::save(array("label", "href", "image"));
		fn_InitAuthAllUser(get_class($this), $this->numero);
		return $return;
	}
	public function disp($commandactive=true, $jsremove=false) { 
		$classcss='noteditable';
		if ($jsremove)
			$classcss='';		
		$retour = '<div class="Container animated flipInY ' . $GLOBALS["config"]->csscontainer .' ' .  $classcss.'" data-id="'.$this->config_class.'_'.$this->numero.'" >';
		$retour .= '<div class="Entete '.get_class($this).'_label_'.$this->numero.'" >'.$this->label.'</div>';
		$retour .= '<a href="'.$this->href.'" target="_new">';
		$retour .= '<img class="Image Pointer ' . get_class($this) . '_image_' . $this->numero . '" alt="lie," src="'.$this->image.'"/>';
		$retour .= '</a>';
		$retour .= '<div class="Caption"></div>';
		$retour .= '<i class="js-remove fa fa-trash-o fa-2x"></i>';	
		$retour .= '</div>';
		return $retour;
	}
	public function disp_list() {
		$return  = '<a href="?app=Mn&amp;page=Add&amp;class=' . __class__ . '&amp;numero=' . $this->numero . '&amp;del=true" class="'. $GLOBALS["classDispList"] .'">';
		$return .= '	<div class="well profile_view">';
		$return .= '		<div class="col-sm-12">';
		$return .= '			<div>';
		$return .= '                <span class="col-xs-9 left brief searchable filterable" style="margin-top: 4px;" data-beginletter="'.strtoupper(substr($this->label,0,1)).'">';
		$return .= '					<span class="badge bg-red">'.$this->numero.'</span>';
		$return .= '					<span class="h2">'.$this->label.'</span>';
		$return .= '					</span>';
		$return .= '            </div>';
		$return .= '			<div class="left col-xs-12">';
		$return .= '			</div>';
		$return .= '		</div>';
		$return .= '	</div>';
		$return .= '</a>';
		echo $return;
	}
	public function mysql_load($when = NULL)
	{
	}
}
?>