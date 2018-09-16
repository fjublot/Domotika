<?php
	header('Content-Type: application/json; charset=utf-8');
	global $db;
	
	// Chargement des users
	$Lusers = array();
	$Lusers[0] = "Inconnu";
	if ( isset($GLOBALS["config"]->users) ) {
		$xpath = "//users/user";
		$ListUser = $GLOBALS["config"]->xpath($xpath);
		foreach($ListUser as $user) {
			$Lusers[(string)$user->attributes()->numero] = $user->label;
		}
	}
	// on récupère les critères sélectionnés
	$nbvar = extract($_REQUEST);
	$i = 0;
	$choix = array();
	$choix[0]="";
	// si la variable est présente, on lui affecte une place dans le tableau 'choix[]', qui nous servira ensuite à construire le WHERE de la requête.
	if(isset($type) && !empty($type)) { $choix[$i++] = "`type` = '$type'";}
	if(isset($fromdate) && !empty($fromdate)) {$choix[$i++] = "`time` >= '$fromdate'";}
	if(isset($todate) && !empty($todate)) {$choix[$i++] = "`time` <= '$todate'";}
	if(isset($userid) && !empty($userid)) { $choix[$i++] = "`userid` = '$userid'";}
	// on insère les éléments remplis dans une variable $critere, en commençant par la première occurrence, puis on boucle
	$critere = $choix[0];
	for($j=1;$j<$i;$j++) {
		$critere .= " AND ".$choix[$j]." ";
	}
	$userids = "0";
	$usernames = "'N/A'";
	
	for($j=1;$j<count($Lusers);$j++) {
		$userids .= ', '.$j;
		$usernames .= ", '".$Lusers[$j]."(".$j.")'";
	}
	$sql_username = "IFNULL(ELT(FIELD(user_id, ".$userids."),".$usernames."), 'N/A') AS username";

	// enfin on fait la requête si $i >0, ça veut dire qu'il y a des critères
	if($i > 0) { 
		// requete de selection
		$sql = "SELECT id, ".$sql_username.", type, texte, INET_NTOA(ipfrom) as ipfrom, timeutc, concat(convert_tz(timeutc,'GMT', timezone),',',lpad(microtime,3,'0'),' (',timezone,')') as usertime, timezone FROM trace WHERE " . $critere . " ORDER BY id desc";
	}
	else { 
		/* si $i = 0, alors l'utilisateur n'a pas saisi de critère, là soit on fait la même requete mais sans "WHERE $critere", soit on lui demande de saisir au moins un critère. */
		$sql = "SELECT id, ".$sql_username.", type, texte, INET_NTOA(ipfrom) as ipfrom, timeutc, concat(convert_tz(timeutc,'GMT', timezone),',',lpad(microtime,3,'0'),' (',timezone,')') as usertime, timezone FROM trace ORDER BY id desc";
	}
	
	if (isset($limit) && !empty($limit)) { 
		$sql .= ' limit ' . $limit;
	}
	else { 
		$sql .= ' limit 5000';
	}

	if(isset($draw) && !empty($draw)) 
	{ $draw=$draw;} 
	else {$draw=1;}
	//récupération des données
	$rows = $db->runQuery($sql);
	$results = array(
		"sql" => $sql,
		"nbvar" => $nbvar,
		"draw" => $draw,
		"recordsTotal" => count($rows),
		"recordsFiltered" => count($rows),
		"data" => $rows
	);

	echo json_encode($results);
?>