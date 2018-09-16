<?php
/*----------------------------------------------------------------*
* Titre : top.php                                                 *
* Classe de ptop                                                  *
*-----------------------------------------------------------------*
* Créé par    : Thomas            Le : 14/11/2011  Version : 1.00 *
* Modifié par : XXXXXXXX          Le : XX/XX/XXXX  Version : 1.01 *
*-----------------------------------------------------------------*/

class top
{
	public $numero, $label;
	public $update_time;
	public $creation;
	public $conf_file, $valeur;
	public $subclass = array();
	public $containclass = array();
	public $config_class;
	public function __construct($numero = "", $info = null) {
		$this->numero = (string)$numero;
		if ( ! isset($this->config_class) || ($this->config_class=="") )
			$this->config_class = get_class($this);
	
	
		if ( $info == null && isset($GLOBALS["config"]->{$this->config_class."s"}) ) {
			
			$xmlData = $GLOBALS["config"]->xpath('//'.$this->config_class.'s/'.$this->config_class.'[@numero="'.$this->numero.'"]');
			
			if ( count($xmlData) == 1 )
				$info = $xmlData[0];
			else
				$info = null;
		}
		if ( $info != null ) {
			foreach($info as $key => $value) {		
				if ( property_exists(get_class($this), $key) && is_array($this->$key) ) {
					if ( strpos ((string)$value, ',') === false ) {
						if ( (string)$value != "" )
							array_push($this->$key, (string)$value);
					}
					else {
						$this->$key = explode(',', (string)$value);
					}
				}
				elseif ( property_exists(get_class($this), $key) ) {
					$this->$key = (string)$value;
					if ( $this->$key == "#N/A" )
						$this->$key = "";
				}
			}
		}
		if ($this->label!="")
			$this->creation = false;
		else
			$this->creation = true;
		return $this;
	}
	
	public function del()
	{
		$doc = new  domDocument();
		$doc->preserveWhiteSpace = FALSE;
		$doc->formatOutput = TRUE;
		$doc->load($GLOBALS["config_file"]);
		$xpath= new DomXPath($doc);
		$query='//'.$this->config_class.'s/'.$this->config_class.'[@numero="'.$this->numero.'"]';
		$nodes = $xpath->query($query);
		foreach ($nodes as $item)
		{
			$item->parentNode->removeChild($item);
		}
		$doc->save($GLOBALS["config_file"]);
		$result[]= array('message' => fn_GetTranslation('deleted', $this->config_class."s",$this->numero));
		return $result;
	}
	public function form($page = null)
	{
	}
	public function advancedobject()
	{
	}
	public function receive_form($list_data = array()) {
		foreach($list_data as $key) {   
			if ( array_key_exists($key, $_REQUEST) && isset($_REQUEST[$key]) )
				$this->$key = $_REQUEST[$key];
			else
				$this->$key = "";
		}
		
		return array(true, "");
	}
	
	public function save($list_data = array()) {
		foreach($list_data as $key)
		{
			if ( ! is_array($this->$key) && (string)$this->$key == "" )
				$this->$key = "#N/A";
		}
		$doc = new domDocument();
		$doc->preserveWhiteSpace = FALSE;
		$doc->formatOutput = TRUE;
		$doc->load($GLOBALS["config_file"]);
		$xpathdoc= new DOMXPath($doc);
		$creation = true;
		if ( isset($this->numero) && $this->numero != "" )
		{
			$noeuds = $xpathdoc->query('//'.$this->config_class.'s/'.$this->config_class.'[@numero="'.$this->numero.'"]');
			if ( $noeuds->length == 1 )
			{
				$creation = false;
			}
		}
		if ( $creation ) {
			if ( ! isset($this->numero) || ! is_int($this->numero) )
			{
				$this->numero = 1;
				$List = $xpathdoc->query('//'.$this->config_class.'s/'.$this->config_class.'[@numero="'.$this->numero.'"]');
				while ( $List->length <> 0 )
				{
					$this->numero++;
					$List = $xpathdoc->query('//'.$this->config_class.'s/'.$this->config_class.'[@numero="'.$this->numero.'"]');
				}
			}
			$List = $xpathdoc->query('//'.$this->config_class.'s');
			if ( $List->length == 0 )
			{
				$List = $xpathdoc->query('//config');
				$List = $List->item(0);
				$New = $doc->createElement($this->config_class.'s');
				$List = $List->appendChild($New);
			}
			else
			{
				$List = $List->item(0);
			}
			$New = $doc->createElement($this->config_class);
			$New->setAttribute("numero", $this->numero);
			foreach($list_data as $key)
			{
				if ( is_array($this->$key) )
				{
					$NewCaracteristique = $doc->createElement($key, join(',', $this->{$key}));
				}
				else
					$NewCaracteristique = $doc->createElement($key, (string)$this->{$key});
				$New->appendChild($NewCaracteristique);
			}
			$List->appendChild($New);
			$result = array('class' => $this->config_class, 'numero' => $this->numero, 'reload' => true, 'message' => fn_GetTranslation('create_done', $this->config_class, $this->numero));
		}
		else {
			$noeuds = $noeuds->item(0);
			foreach($list_data as $key)
			{
				$noeud_key = $xpathdoc->query('//'.$this->config_class.'s/'.$this->config_class.'[@numero="'.$this->numero.'"]/'.$key);
				if ( $noeud_key->length == 1 )
				{
					$cible = $noeud_key->item(0);
					$taille = strlen($cible->nodeValue);
					if ( $taille != 0 )
					{
						$cible->firstChild->deleteData(0,$taille);
						if ( is_array($this->$key) )
						{
							$value = join(',', $this->{$key});
							if ( strlen($value) != 0 )
								$cible->firstChild->insertData(0, $value);
						}
						else
							if ( strlen($this->$key) != 0 )
								$cible->firstChild->insertData(0, (string)$this->$key);
					}
					else
					{
						$cible->parentNode->removeChild($cible);
						if ( is_array($this->$key) )
						{
							$NewCaracteristique = $doc->createElement($key, join(',', $this->{$key}));
						}
						else
							$NewCaracteristique = $doc->createElement($key, (string)$this->{$key});
						$cible->appendChild($NewCaracteristique);
					}
				}
				else
				{
					if ( is_array($this->$key) )
					{
						$NewCaracteristique = $doc->createElement($key, join(',', $this->{$key}));
					}
					else
						$NewCaracteristique = $doc->createElement($key, (string)$this->{$key});
					$noeuds->appendChild($NewCaracteristique);
				}
			}
			$result = array('class' => $this->config_class, 'numero' => $this->numero, 'reload' => false, 'message' => ucfirst(fn_GetTranslation('changes_recorded')));
		}
		
		$doc->save($GLOBALS["config_file"]);
		foreach($list_data as $key)
		{
			if ( ! is_array($this->$key) && (string)$this->$key == "#N/A" )
				$this->$key = "";
		}
		$this->update_time = time();
		$GLOBALS["config"] = simplexml_load_file($GLOBALS["config_file"]);
		if ( file_exists($GLOBALS["default_config_file"]) ) {
			$default_config = simplexml_load_file($GLOBALS["default_config_file"]);
			fn_MergeXmlChild($GLOBALS["config"], $default_config);
		}
		$result['errorcode'] = 0;
		return $result;
	}
	public function verif_before_del()
	{
		return true;
	}
	public function disp()
	{
		return '<div class="Entete'.$this->config_class.'" id="'.$this->config_class.'_'.$this->numero.'">'.$this->label.'</div>';
	}
	public function disp_li($first=null)
	{
		//return '<li id="'.$this->config_class.'_'.$this->numero.'">'.$this->label.'</li>';
		$return = '<li id="'.$this->config_class.'_'.$this->numero.'">';
		$return.= $this->disp();
		$return.= '</li>';
		return $return;
	}
	
	public function getxmlvalue($xmlStatus)
	{	if ( isset($xmlStatus->{$this->xmlid}) )
			$this->valeur = (string)$xmlStatus->{$this->xmlid};
		else
			$this->valeur = "";
		return $this->valeur;
	}
	public function asxml($detail = false)
	{
		return '<element value="'.$this->numero.'" text="'.$this->label.'"></element>';
	}
	public function update()
	{
		if ( isset($this->timezone) && $this->timezone == "" )
		{
			if ( isset($GLOBALS["config"]->general->timezone) )
				$this->timezone = $GLOBALS["config"]->general->timezone;
			else
				$this->timezone = "UTC";
		}
	}
	public function mysql_save()
	{ 
		if ( isset($this->valconv) )
			$current_valeur = $this->valconv;
		else
			$current_valeur = $this->valeur;
		
		if ( isset($GLOBALS["config"]->general->mysql) && $GLOBALS["config"]->general->mysql == 'on' && isset($current_valeur) ) {
			global $db;
			//$db->beginTransaction;
			$query  = "SELECT `etat`, `time` FROM `".$this->config_class."` WHERE ";
			$query .= "`numero` = ".$this->numero;
			$query .= " ORDER BY `time` DESC LIMIT 1";
			$rows = $db->runQuery($query);
			fn_Trace($query, 'select');
			//	echo $sql."<br>";
			unset($query);
			if (count($rows)==0) {
				$sql = "INSERT INTO `".$this->config_class."`(`numero`, `etat`, `time`) VALUES (:numero, :etat, :time);";
				$params = array(
					'numero' => $this->numero,
					'etat' => $current_valeur,
					'time' => $this->update_time
				);
			}
			else {
				foreach ( $rows as $row) { //1 seul enregistrement
					if ( ( (float)$row["etat"] != round ((float)$current_valeur, 3) ) || ( $row["etat"] != $current_valeur ) ) {
						$sql = "INSERT INTO `".$this->config_class."`(`numero`, `etat`, `time`) VALUES (:numero, :etat, :time);";
						$params = array(
							'numero' => $this->numero,
							'etat' => $current_valeur,
							'time' => $this->update_time
						);
					}
				}
			}
			if ( isset($sql) ) {
				fn_Trace($sql,'sql');
				$db->runQuery($sql, $params);
			}
			else {
				$sql=false;
			}
			//$db->Commit;
		}
		return $sql;
	}
	
	public function mysql_load($when = NULL)
	{
 		if ( isset($GLOBALS["config"]->general->mysql) && $GLOBALS["config"]->general->mysql == 'on' )
		{
			global $db;
			$query  = "SELECT etat, time ";
			$query .= "FROM ".$this->config_class . " ";
			$query .= "WHERE numero = :numero ";
			if ( ! is_null($when) )
				$query .= " and time <= :when ";
			$query .= " ORDER BY time DESC LIMIT 1";

			if ( ! is_null($when) ) {
				$params = array(
					'numero' => $this->numero,
					'when'   => $when
				);
			}
			else {
				$params = array(
					'numero' => $this->numero,
				);
			}
		fn_Trace($query ,"query");

			$rows = $db->runQuery($query, $params);
      // Le select ne retourne rien
      if (count($rows)==0) {
				if ( property_exists(get_class($this), 'valconv') )
					$this->valconv = 0;
				else
					$this->valeur = 0;
				$this->update_time = 0;
			}
      // Le select ramène quelque chose
      else {
        foreach ($rows as $row) {
  				if ( property_exists(get_class($this), 'valconv') )
  					$this->valconv = $row["etat"];
  				else
  					$this->valeur = $row["etat"];
  				$this->update_time = $row["time"];
        }
      }
			if ( isset($this->precision) && $this->precision != "" )
				$this->valeur = round ($this->valeur, (int) $this->precision );
	}
}

/* position($n1) > position($n2), dommage compareDocumentPosition n'est pas implémentée */
public function swap(DOMNode $n1, DOMNode $n2) {
    //$n1 = $n2->parentNode->removeChild($n1); // Inutile, fait en interne, conformément à la spéc
    $n2->parentNode->insertBefore($n1, $n2);
}
public function moveUp() {
    if ($this->previousSibling) {
        $this->parentNode->insertBefore($this, $this->previousSibling);
    }
}
public function moveDown() {
    if ($this->nextSibling) {
        $this->parentNode->insertBefore($this->nextSibling, $n);
    }
}
	public function jsfields($list_data)
	{
		$return='';
		foreach($list_data as $key)
		{
			$return .='var '.$key.' = $(\'#'.$key.'\').attr(\'value\');';
		}
		return $return;
	}
	public function urllistfields($list_data)
	{
		$return='';
		foreach($list_data as $key)
		{
			$return .='"&'.$key.'="+'.$key.'+';
		}
		$return .='""';
		return $return;
	}
	public function required_field()
	{
		return true;
	}
	public function __destruct()
	{
	}
	public function disp_list() {
	}

}
?>