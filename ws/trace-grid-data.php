<?php
	header('Content-Type: application/json; charset=utf-8');
	ini_set('memory_limit', '-1');

	/* Database connection end */
	global $db;

	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;


	$columns = array( 
		// datatable column index  => database column name
		0 => 'id', 
		1 => 'user_id',
		2 => 'type',
		3 => 'from',
		4 => 'texte',
		5 => 'usertime'
	);




// getting total number records without any search
$sql = "SELECT *  FROM trace order by id desc";
$totalData = $db->runQuery($sql,null, 3);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	// Chargement des users
	$Lusers = array();
	if ( isset($GLOBALS["config"]->users) ) {
		$xpath = "//users/user";
		$ListUser = $GLOBALS["config"]->xpath($xpath);
		foreach($ListUser as $user) {
			$Lusers[(string)$user->attributes()->numero] = $user->label;
		}
	}
	$userids = "0, 255";
	$usernames = "'Inconnu', 'crontab'";
	$j = 0;
	foreach ($Lusers as $Luser) {
		$j++;
		$userids .= ', '.$j;
		$usernames .= ", '".$Luser."(".$j.")'";
	}
	$sql_username = "IFNULL(ELT(FIELD(user_id, ".$userids."),".$usernames."), user_id) AS username";



$sql = "SELECT id, ".$sql_username.", type, texte, concat(convert_tz(timeutc,'GMT',timezone),',',lpad(microtime,3,'0'),' (',timezone,')') as usertime, INET_NTOA(ipfrom) as ipfrom, timeutc, timezone ";
$sql .= "FROM trace where 1=1 ";

// getting records as per search parameters
if( !empty($requestData['columns'][0]['search']['value']) ){   //user id
	$sql.=" AND id LIKE '".$requestData['columns'][0]['search']['value']."%' ";
}
if( !empty($requestData['columns'][1]['search']['value']) ){  //username
	$sql.=" AND username LIKE '".$requestData['columns'][1]['search']['value']."%' ";
}
if( !empty($requestData['columns'][2]['search']['value']) ){  //type
	$sql.=" AND type LIKE '".$requestData['columns'][2]['search']['value']."%' ";
}
if( !empty($requestData['columns'][3]['search']['value']) ){  //ip
	$sql.=" AND INET_NTOA(ipfrom) LIKE '".$requestData['columns'][3]['search']['value']."%' ";
}
if( !empty($requestData['columns'][4]['search']['value']) ){  //texte
	$sql.=" AND texte LIKE '".$requestData['columns'][4]['search']['value']."%' ";
}


if ($requestData['length'] != -1)
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";  // adding length
else
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."    ";  // adding length

$query = $db->runQuery($sql);
$data = array();
foreach ($query as $row) {
	$nestedData=array(); 
	$nestedData[] = $row["id"];
	$nestedData[] = $row["username"];
	$nestedData[] = $row["type"];
	$nestedData[] = $row["ipfrom"];	
	$nestedData[] = $row["texte"];
	$nestedData[] = $row["timeutc"];
	$data[] = $nestedData;
}


$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"sql"			  => $sql, 
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format

?>
