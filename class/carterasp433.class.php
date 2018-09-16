<?php
/*----------------------------------------------------------------*
* Titre : ipx800.php                                               *
* Classe de carte                                                 *
*-----------------------------------------------------------------*/
class carterasp433 extends carte
{
	public $user, $password, $gpioport, $remoteid;
	public function __construct($numero="", $info = null)
	{
		$this->config_class = "carte";
		parent::__construct($numero, $info);
		$this->subclass = array("rasp433");
		return $this;
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function form($page=null)
	{
		$return  = parent::form();
		$return .= fn_HtmlSelectField('gpioport', 'gpioport', 'carte.gpioport',"",false,false);
		$return .= fn_HtmlInputField('remoteid', $this->remoteid, 'text', 'remoteid', 'carte.remoteid', "", false);
 		$return .= fn_HtmlInputField('user', $this->user, 'text', 'compte', 'carte.user', "", false);
 		$return .= fn_HtmlInputField('password', $this->password, 'text','password', 'carte.password', '');
		return $return;
	}
	public function js()
	{
 		$return = parent::js();
		$return .='AjaxLoadSelectJson("gpioport", "class=gpioport433", false, "'.$this->gpioport.'" );';
		$return .='		/* Règles de validation */
				var configvalidate = {
					// ordinary validation config
					form : \'#ajaxform\',
					// reference to elements and the validation rules that should be applied
					validate : {
						\'#label\' : {
						    validation : \'length, alphanumeric\',
							length : \'5-20\'
						},
					}
				};
		';
		return $return;
	}
	public function receive_form($list_data = array()) {
		list($status, $message) = parent::receive_form(array("user", "password", "gpioport", "remoteid"));
		$this->update();
		return array($status, $message);
	}
	public function required_field() {
		return array_key_exists("gpioport", $_POST);
	}
	public function save($list_data = array()) {
		$to_save = array("user", "password", "gpioport", "remoteid");
		$return = parent::save($to_save);
		return $return;
  }

	public function geturl($withpassword = true)
	{
		$url = 'http://';
		if ( $this->user != '' && $withpassword = true)
		{
			$url .= $this->user.':'.$this->password.'@';
		}
		$url .= $this->ip;
		if ( $this->port != '' )
		{
			$url .= ':'.$this->port;
		}
		return $url."/";
	}
	public function setalloff()
	{
	}
	public function setallon()
	{
	}
	public function fn_SetRasp433($rasp433_id, $value)
	{
		fn_Trace("Carte ".$this->numero." : set rasp433 ".$rasp433_id." ".$value, "carte", $this->timezone);
		$url = $this->geturl().'?app=Ws&page=radioemission&gpioport='.$this->gpioport.'&remoteid='.$this->remoteid.'&numero='.$rasp433_id.'&value='.$value;
		fn_Trace($url, "carte", $this->timezone);
		return file($url);
	}

	public function backpush($message, $class, $numero)
	{
		echo '<li>'."Carte ".$this->numero." : back push ".$message.' ('.$this->timezone.')</li>';
		fn_Trace("Carte ".$this->numero." : back push ".$message, "push", $this->timezone);
		$this->update_time = time();
		list($objet, $etat) = explode(" ", $message);
		echo '<li>Current='.$class.'('.$numero.') Etat : '.$etat.'</li>';
		fn_Trace("Carte ".$this->numero." : back push ".$class."(".$numero.")", "push", $this->timezone);
		$current = new $class($numero);
		if ( isset($current) )
		{
			$MsgPush = $current->backpush($etat, $this->update_time);
			echo '<li>MsgPush='.htmlspecialchars($MsgPush, ENT_QUOTES).'</li>';
		}
	}

	public function update()
	{
	}
}
?>