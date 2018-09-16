<?php
/****************************************************************************
*   Récupération de la couleur du jour Tempo depuis le site EDF             *
*                                                                           *
*   Script libre de droit. Merci de ne pas modifier cet en-tête et de ne    *
*   pas retirer les commentaires.                                           *
*   Créé par Lionel FÉVRIER - LionelF sur le forum de GCE Electronics       *
*   Modifier par Thomas Mars - guenneguez_t sur le forum de GCE Electronics *
*****************************************************************************
* Librairie :
* get_couleur_tempo
*	argument
*		$when
* 		0 ou sans -> aujourd'hui
*		1 		  -> demain
*	retour :
*		0		  -> bleu
*		1		  -> blanc
*		2		  -> rouge
* get_couleur_ejp
*	argument
*		$zone
* 		0 ou sans -> Zone Nord
*		1 		  -> Zone Provence, Alpes, Côte d'Azur
*		2 		  -> Zone Ouest
*		3 		  -> Zone Sud
*		$when
* 		0 ou sans -> aujourd'hui
*		1 		  -> demain
*	retour :
*		0		  -> non ejp
*		1		  -> ejp
*/

function get_couleur_tempo($when = 0)
{
	$page_couleur_tempo = "http://bleuciel.edf.com/abonnement-et-contrat/les-prix/les-prix-de-l-electricite/option-tempo/la-couleur-du-jour-2585.html";
	$lines = file($page_couleur_tempo);
	$find = 0;

	// on va scruter le fichier ligne par ligne
	foreach($lines as $line_num => $line)
	{
		//on va chercher la zone de la page où EDF affiche la couleur du tempo du jour
		if ( preg_match("#Tempo d'aujourd'hui#", $line) && $when == 0 )
		{
			$find=1; //quand on trouve on met ce drapeau à 1
		}
		//on va chercher la zone de la page où EDF affiche la couleur du tempo du jour
		if ( preg_match("#Tempo de demain#", $line) && $when == 1 )
		{
			$find=1; //quand on trouve on met ce drapeau à 1
		}

		if ($find == 1 && preg_match("#<li class=\"red\">X</li>#", $line)) //une fois le drapeau à 1, on cherche la croix dans la zone rouge
		{
			return 2;
			break;
		}
		elseif ($find == 1 && preg_match("#<li class=\"white\">X</li>#", $line)) //ou bien la croix dans la zone blanche
		{
			return 1;
			break;
		}
		elseif ($find == 1 && preg_match("#<li class=\"blue\">X</li>#", $line)) //ou enfin la croix dans la zone bleue
		{
			return 0;
			break;
		}
	}
	return -1;
}

function get_couleur_ejp($zone, $when = 0)
{
	$page_couleur_tempo = "http://bleuciel.edf.com/abonnement-et-contrat/les-prix/les-prix-de-l-electricite/option-ejp/l-observatoire-2584.html";
	$lines = file($page_couleur_tempo);
	$zone +=2;
	$find = 0;

	// on va scruter le fichier ligne par ligne
	foreach($lines as $line_num => $line)
	{
		//on va chercher la zone de la page où EDF affiche la couleur du tempo du jour
		if ( preg_match("#EJP aujourd'hui#", $line) && $when == 0 )
		{
			$find=1; //quand on trouve on met ce drapeau à 1
		}
		//on va chercher la zone de la page où EDF affiche la couleur du tempo du jour
		if ( preg_match("#EJP demain#", $line) && $when == 1 )
		{
			$find=1; //quand on trouve on met ce drapeau à 1
		}

		if ($find == 1 && preg_match("#h".$zone.".*ejp_oui#", $line)) //une fois le drapeau à 1, on cherche la croix dans la zone rouge
		{
			return 1;
			break;
		}
		elseif ($find == 1 && preg_match("#h".$zone.".*ejp_non#", $line)) //ou bien la croix dans la zone blanche
		{
			return 0;
			break;
		}
	}
	return -1;
}
?>