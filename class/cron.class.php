<?php
/*----------------------------------------------------------------*
* Titre : cron.php                                                *
* Classe de cron                                                  *
*-----------------------------------------------------------------*/
require_once($GLOBALS["mvc_path"]."top.php");
class cron extends top
{
	public $label, $actif, $minute, $heure, $jour_m, $mois, $jour_s, $scenario;
	public function __construct($numero="", $info = null)
	{
		parent::__construct($numero, $info);
		return $this;
	}
	public function form($page=null)
	{
		$return  =  fn_HtmlStartPanel($this->label, fn_GetTranslation(__class__), __class__, $this->numero);
		$return .= '		<div class="x_content">';
		$return .= '			<form name="ajaxform" id="ajaxform" class="form-horizontal form-label-left">';
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
		$return .= fn_HtmlInputField('label', $this->label, 'text', 'cron_name', 'cron.label', '');
		$return .= fn_HtmlBinarySelectField('actif', $this->actif, 'activ_cron', 'cron.actif');
		$return .= fn_HtmlInputField('minute', $this->minute, 'text', 'minute', 'cron.minute', '');
		$return .= fn_HtmlInputField('heure', $this->heure, 'text', 'hour', 'cron.heure', '');
		$return .= fn_HtmlInputField('jour_m', $this->jour_m, 'text', 'day_of_month', 'cron.jour_m', '');
		$return .= fn_HtmlInputField('mois', $this->mois, 'text', 'month', 'cron.mois', '');
		$return .= fn_HtmlInputField('jour_s', $this->jour_s, 'text', 'day_of_week', 'cron.jour_s', '');
 		$return .= fn_HtmlSelectField('scenario', 'scenario', 'cron.scenario',"",false,true);
		return $return;
	}
	public function js()
	{
		$return='AjaxLoadSelectJson("scenario", "class=scenario", false, "'.$this->scenario.'" );';
		return $return;
	}
	public function receive_form($list_data = null)
	{
		list($status, $message) = parent::receive_form(array("label", "minute", "heure", "jour_m", "mois", "jour_s", "scenario", "actif"));
		return array($status, $message);
	}
	public function save($list_data = null)
	{
		return parent::save(array("label", "minute", "heure", "jour_m", "mois", "jour_s", "scenario", "actif"));
	}
	public function time_to_run($current_time)
	{
		// Test les minutes
		$run_minute = false;
		unset($frequence);
		foreach(explode(",", $this->minute) as $minute)
		{
			if ( preg_match("!\*/[0-9]+!", $minute) )
				list($minute, $frequence) = explode("/", $minute);
			if ( $minute == date("i", $current_time) )
			{
				$run_minute = true;
			}
			elseif ( $minute == "*" )
			{
				if ( isset($frequence) )
				{
					if ( floor(date("i", $current_time)/$frequence) == date("i", $current_time)/$frequence )
						$run_minute = true;
				}
				else
					$run_minute = true;
			}
			elseif ( preg_match("/([0-9]*)\-([0-9]*)/", $minute, $reg) )
			{
				for($count = $reg[1]; $count <= $reg[2]; $count++)
				{
					if ( $count == date("i", $current_time) )
					{
						$run_minute = true;
					}
				}
			}
		}
		// Test les heures
		$run_heure = false;
		unset($frequence);
		foreach(explode(",", $this->heure) as $heure)
		{
			if ( preg_match("!\*/[0-9]+!", $heure) )
				list($heure, $frequence) = explode("/", $heure);
			if ( $heure == date("G", $current_time) )
			{
				$run_heure = true;
			}
			elseif ( $heure == "*" )
			{
				if ( isset($frequence) )
				{
					if ( floor(date("G", $current_time)/$frequence) == date("G", $current_time)/$frequence )
						$run_heure = true;
				}
				else
					$run_heure = true;
			}
			elseif ( preg_match("/([0-9]*)\-([0-9]*)/", $heure, $reg) )
			{
				for($count = $reg[1]; $count <= $reg[2]; $count++)
				{
					if ( $count == date("G", $current_time) )
					{
						$run_heure = true;
					}
				}
			}
		}
		// Test le jour du mois
		$run_jour_m = false;
		unset($frequence);
		foreach(explode(",", $this->jour_m) as $jour_m)
		{
			if ( preg_match("!\*/[0-9]+!", $jour_m) )
				list($jour_m, $frequence) = explode("/", $jour_m);
			if ( $jour_m == date("j", $current_time) )
			{
				$run_jour_m = true;
			}
			elseif ( $jour_m == "*" )
			{
				if ( isset($frequence) )
				{
					if ( floor(date("j", $current_time)/$frequence) == date("j", $current_time)/$frequence )
						$run_jour_m = true;
				}
				else
					$run_jour_m = true;
			}
			elseif ( preg_match("/([0-9]*)\-([0-9]*)/", $jour_m, $reg) )
			{
				for($count = $reg[1]; $count <= $reg[2]; $count++)
				{
					if ( $count == date("j", $current_time) )
					{
						$run_jour_m = true;
					}
				}
			}
		}
		// Test le mois
		$run_mois = false;
		unset($frequence);
		foreach(explode(",", $this->mois) as $mois)
		{
			if ( preg_match("!\*/[0-9]+!", $mois) )
				list($mois, $frequence) = explode("/", $mois);
			if ( $mois == date("n", $current_time) )
			{
				$run_mois = true;
			}
			elseif ( $mois == "*" )
			{
				if ( isset($frequence) )
				{
					if ( floor(date("n", $current_time)/$frequence) == date("n", $current_time)/$frequence )
						$run_mois = true;
				}
				else
					$run_mois = true;
			}
			elseif ( preg_match("/([0-9]*)\-([0-9]*)/", $mois, $reg) )
			{
				for($count = $reg[1]; $count <= $reg[2]; $count++)
				{
					if ( $count == date("n", $current_time) )
					{
						$run_mois = true;
					}
				}
			}
		}
		// Test le jour de la semaine
		$run_jour_s = false;
		unset($frequence);
		foreach(explode(",", $this->jour_s) as $jour_s)
		{
			if ( preg_match("!\*/[0-9]+!", $jour_s) )
				list($jour_s, $frequence) = explode("/", $jour_s);
			if ( $jour_s == date("N", $current_time) )
			{
				$run_jour_s = true;
			}
			elseif ( $jour_s == "*" )
			{
				if ( isset($frequence) )
				{
					if ( floor(date("N", $current_time)/$frequence) == date("N", $current_time)/$frequence )
						$run_jour_s = true;
				}
				else
					$run_jour_s = true;
			}
			elseif ( preg_match("/([0-9]*)\-([0-9]*)/", $jour_s, $reg) )
			{
				for($count = $reg[1]; $count <= $reg[2]; $count++)
				{
					if ( $count == date("N", $current_time) )
					{
						$run_jour_s = true;
					}
				}
			}
		}
		return $run_minute && $run_heure && $run_jour_m && $run_mois && $run_jour_s;
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
		$return .= '				<p class="searchable">'.fn_GetTranslation("scenario").' : '.$this->scenario.'</p>';
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