<?php
	header('Content-Type: application/json; charset=utf-8');

/* Database connection end */
	global $db;

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;


$columns = array( 
// datatable column index  => database column name
	0 => 'time', 
	1 => 'etat',
	2 => 'etattxt'
);




// getting total number records without any search
$sql = "SELECT * FROM ".$class." where numero=".$numero." order by time desc";
$totalData = $db->runQuery($sql,null, 3);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

$current = new $class($numero);

switch ($class) {
    case 'relai':
		$MsgOn = str_replace("%etat%", fn_GetTranslation('etat_down'), $current->messageon);
		$MsgOff = str_replace("%etat%", fn_GetTranslation('etat_up'), $current->messageoff);
		$MsgOn = str_replace("%label%", $current->label, $MsgOn);
		$MsgOff = str_replace("%label%", $current->label, $MsgOff);
		$sql = "SELECT *, if(etat=1,'".$MsgOn."','".$MsgOff."') as etattxt    FROM ".$class." where numero=".$numero;
        break;
   case 'btn':
		$MsgOn = str_replace("%etat%", fn_GetTranslation('etat_open'), $current->messagedn);
		$MsgOff = str_replace("%etat%", fn_GetTranslation('etat_close'), $current->messageup);
		$MsgOn = str_replace("%label%", $current->label, $MsgOn);
		$MsgOff = str_replace("%label%", $current->label, $MsgOff);
		$sql = "SELECT *, if(etat=1,'".$MsgOn."','".$MsgOff."') as etattxt    FROM ".$class." where numero=".$numero;
        break;
   case 'razdevice':
		$MsgOn = str_replace("%etat%", fn_GetTranslation('etat_down'), $current->messageon);
		$MsgOff = str_replace("%etat%", fn_GetTranslation('etat_up'), $current->messageoff);
		$MsgOn = str_replace("%label%", $current->label, $MsgOn);
		$MsgOff = str_replace("%label%", $current->label, $MsgOff);
		$sql = "SELECT *, if(etat=1,'".$MsgOn."','".$MsgOff."') as etattxt FROM ".$class." where numero=".$numero;
        break;
    default:
		$sql = "SELECT *, '' as etattxt    FROM ".$class." where numero=".$numero;
}
// getting records as per search parameters
if( !empty($requestData['columns'][0]['search']['value']) ){   //time
	$sql.=" AND time LIKE '".$requestData['columns'][0]['search']['value']."%' ";
}
if( !empty($requestData['columns'][1]['search']['value']) ){  //etat
	$sql.=" AND etat LIKE '".$requestData['columns'][1]['search']['value']."%' ";
}
if( !empty($requestData['columns'][2]['search']['value']) ){  //etat
	$sql.=" AND etattxt LIKE '".$requestData['columns'][2]['search']['value']."%' ";
}

$totalFiltered = $db->runQuery($sql, null, 3);	
if ($requestData['length'] != -1)
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";  // adding length
else
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."    ";  // adding length

$query = $db->runQuery($sql);
	


$data = array();
foreach ($query as $row) {
	$nestedData=array(); 
	$nestedData[] = date('Y-m-d H:i:s', $row["time"]);
	$nestedData[] = $row["etat"];
	$nestedData[] = $row["etattxt"];
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
