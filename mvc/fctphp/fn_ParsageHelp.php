<?php
	function fn_ParsageHelp($document) {
		$dom = new DomDocument;
        $dom->load($document);
        $resultat_html = '';
		$summary = $dom->getElementsByTagName('summary')->item(0);
		$resultat_html .= fn_ParsageEnfants($summary);
		return $resultat_html;
	}
	
	function fn_ParsageEnfants($noeud) { // Fonction de parsage d'enfants 
			if(!isset($accumulation)) // Si c'est la première balise, on initialise $accumulation 
					$accumulation = '';
			$enfants = $noeud->childNodes; // Les enfants du nœud traité
			$accumulation .= '<!-- ' . count($enfants) . ' -->';
			
			foreach($enfants as $enfant) { // Pour chaque enfant, on vérifie… 
				$accumulation .= '<!-- ' . $enfant->nodeName . ' : ' . $enfant->nodeValue . ' -->';
			
				if ($enfant->nodeName == 'group') { // …group s'il a lui-même des enfants
					$accumulation .= fn_ParsageEnfants($enfant); // Dans ce cas, on revient sur parsage_enfant
				}
				else {  // ... s'il n'en a plus !
					$accumulation .= fn_ParsageNormal($enfant); // On parse comme un nœud normal
				}
			}
			return $accumulation;
			//return fn_ParsageNormal($noeud, $accumulation);
	}
	
	function fn_ParsageNormal($noeud, $contenu_a_inserer='') {
		$file = "help/" . $_SESSION['lang'] . "/" . $noeud->nodeValue; //getAttribute('file'); // On récupère la valeur de l'attribut file
		/*if ($contenu_a_inserer != "")
			$return = $contenu_a_inserer;
		else
			$return = "";
		*/
		$return = 	"<!-- ".$file." -->";
		if ( file_exists($file) ) {
			$data = simplexml_load_file($file);

		}
		else {
			$return .= 	"<!-- ".$file." introuvable -->";		
			$data = new SimpleXMLElement("<help><titre>File ".$file." not found</titre><message></message></help>");
		}
		$return .= '<div class="panel panel-default">';
		$return .= '	<div class="panel-heading">';
		$return .= '		<h4 class="panel-title">';
		$return .= '			<a data-toggle="collapse" data-parent="#helpaccordion" href="#det_sec_'.$file.'">';
		$return .= 					$data->titre;
		$return .= '			</a>';
		$return .= '		</h4>';
		$return .= '	</div>';
		$return .= '	<div id="det_sec_'. $file. '" class="panel-collapse collapse">';
		$return .= '		<div class="panel-body">'.$data->message.'</div>';
		$return .= '	</div>';
		$return .= '</div>';
		
		//return $return; // On renvoie le texte parsé
		return "";
	}
?>
