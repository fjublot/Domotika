<?php
class galerie {
	public $url;
	public $dossier;
	public $titre;
	public $turl;
	public $afurl;
	public $oaff;
	public $amin;
	public $hmin;
	public $theme;
	public function __construct() {
		$this->oaff = $GLOBALS["config"]->captures->ordre;
		$this->amin = $GLOBALS["config"]->captures->miniatures;
		$this->hmin = $GLOBALS["config"]->captures->minheight;
		$this->turl = $GLOBALS["config"]->captures->urlrewrite;
		$this->amin = "1";
		$this->url = str_replace("ViewCaptures.php", "", $_SERVER['PHP_SELF']);
		if(@$_GET['dir']=="") {
			$this->dossier = $rep_captures;
		} else {
			$this->dossier = str_replace("-", "/",$_GET['dir']);
		}
		//$this->titre = $titre_site;
		//$this->theme = $theme;
		$this->oaff = $oaff;
		$this->amin = $amin;
		$this->hmin = $hmin;
		$this->turl = $turl;
		if($this->turl == "0") {
			$this->afurl = "?dir=";
		} elseif($this->turl == "1") {
			$this->afurl = "";
		}
	}
	// Inclut les fichiers javascript necessaires a photis
	// Depuis la version 1.0
	public function js() {
		echo '<script src="ressources/fancyzoom/js-global/FancyZoom.js" type="text/javascript"></script>
		<script src="ressources/fancyzoom/js-global/FancyZoomHTML.js" type="text/javascript"></script>'.PHP_EOL;
	}
	// Inclut les fonctions dans la balise body
	// Depuis la version 1.0
	public function body() {
		echo 'onload="setupZoom()"';
	}
	// Affichage de la liste des dossiers
	// Depuis la version 1.0 - Modifié version 1.3.1
	public function dossiers($avant="", $apres="") {
    	$dir = $this->dossier;
    	if(@$dossier = opendir($dir)) {
    		while($fichier = readdir($dossier)){
	    		$lien = $fichier;
        		if($this->test_dossier($lien)){
					if($this->dossier == $rep_captures) {
						$url = $this->afurl . $rep_captures."-$fichier";
					} else {
						$url = $this->url . $this->afurl . str_replace("/", "-", $this->dossier ) . "-$fichier";
					}
            		echo $avant . "<a href='$url'>$fichier</a>" . $apres . "\n";
        		}
			}
		}
	}
	// Retourne le dossier inferieur a celui visite
	// Depuis la version 1.0 - Modifié version 1.1
	public function dossier_ant($avant="", $contenu="", $apres="") {
		if($this->dossier != $rep_captures) {
			$dossiers = explode("/", $this->dossier ."/");
			$nbd = count($dossiers);
			$i = "0";
			$url = "";
			while($i < ($nbd - 2)) {
				$url .=  $dossiers[$i] . "-";
				$i++;
			}
			$final = substr($url, 0, -1);
			if($final == $rep_captures) {
				if($avant!="" && $contenu!="" && $apres!="") {
					echo $avant . "<a href='". $this->url ."'>" . $contenu . "</a>" . $apres;
				} else {
					return $url_site;
				}
			} else {
				if($avant!="" && $contenu!="" && $apres!="") {
					echo $avant . "<a href='". $this->afurl . $final ."'>" . $contenu . "</a>" . $apres;
				} else {
					return $final;
				}
			}
		}
	}
	// Voir si fichier est un dossier
	// Depuis la version 1.0 - Modifié version 1.3.2
	public function test_dossier($dossier) {
		if($dossier != "." && $dossier != ".." && !preg_match("/Thumbs.db/",$dossier) && $dossier!="index.html" && !preg_match("#.jpg$#i",$dossier) && !preg_match("#.jpeg$#i",$dossier) && !preg_match("#.png$#i",$dossier) && !preg_match("#.gif$#i",$dossier)) {
			return true;
		} else {
			return false;
		}
	}
	// Test pour savoir si le fichier est une image
	// Depuis la version 1.0 - Modifié version 1.3.1
	public function test_image($fichier) {
		if(preg_match("#.jpg$#i",$fichier) || preg_match("#.jpeg$#i",$fichier) || preg_match("#.png$#i",$fichier) || preg_match("#.gif$#i",$fichier)) {
			return true;
		} else {
			return false;
		}
	}
	// Affichage de la liste des images du dossier selectionne
	// Depuis la version 1.0 - Modifié version 1.3.2
	public function images($avant, $apres) {
		if(@$dos = opendir($this->dossier)) {
			if($this->amin == "1") {
				$dossiers = explode("/", $this->dossier);
				$ids = "0";
				$ant = "";
				while($ids < count($dossiers)) {
					if(!is_dir("thumb/".$ant."/".$dossiers[$ids])) {
						mkdir("thumb/".$ant."/".$dossiers[$ids]);
					}
					$ant = $ant . "/". $dossiers[$ids];
					$ids++;
				}
			}
			$i = 0;
			while($fichier = readdir($dos)){
				if($this->test_image($fichier)) { $liens[$fichier] = $this->dossier; }
			}
			if(@$liens!="") {
				if($this->oaff == "1") {
					ksort($liens);
				} elseif($this->oaff == "2") {
					krsort($liens);
				}
				foreach ($liens as $fichier => $this->dossier) {
					$lien = $this->dossier . '/' . utf8_encode($fichier);
					echo $avant . "<a title=\"".$this->nom(utf8_encode($fichier))."\" href=\"$lien\">";
					if($this->amin == "1") {
						if(!file_exists($this->dossier."/thumb/".$fichier)) {
							@image::ResizeImage($lien, $this->dossier."/thumb/".$fichier, 0, $this->hmin);
						} else {
							$dimm = getimagesize($this->dossier."/thumb/".$fichier);
							if($dimm[1] != $this->hmin) {
								@image::ResizeImage($lien, $this->dossier."/thumb/".$fichier, 0, $this->hmin);
							}
						}
						echo "<img src='".$this->dossier."/thumb/".$fichier."' />";
					} else {
						echo "<img src='".$lien."' />";
					}
					echo"</a>" . $apres . "\n";
					$i++;
				}
			} else {
				echo $avant . "<div id='erreur'>Il n'y a pas d'images dans ce dossier !</div>" . $apres . "\n";
			}
		} else {
			echo $avant . "<div id='erreur'>Ce dossier n'existe pas !</div>" . $apres . "\n";
		}
	}
	// Recuperation du nom de l'image
	// Depusi la version 1.0
	public function nom($fichier) {
		if(strrpos($fichier, ".")===false){
			return $fichier;
		} else {
			return substr($fichier, 0, strrpos($fichier, "."));
		}
	}
}
?>