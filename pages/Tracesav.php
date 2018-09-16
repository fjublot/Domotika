<?php
	$return  = fn_HtmlStartPanel("Traces", ucfirst(fn_GetTranslation("traces")), "traces", "none");
	$return .= '<form name="ajaxform" id="ajaxform" class="form-horizontal form-label-left" novalidate>';
	$return .= fn_HtmlStartFieldset(ucfirst(fn_GetTranslation('criteriasearch')), 'criteriasearch', true);
	$return .=  		fn_HtmlSelectField("type", "trace_type", "trace.type","",false,false,false);
	$return .=  		fn_HtmlSelectField("time", "trace_time", "trace.time","",false,false,false);
	$return .=  		fn_HtmlSelectField("userid", "trace_userid", "trace.userid","",false,false,false);
	$return .=  		'<input id="BT_Search" type="submit" value="Search" onClick="UpdateTrace();"/><br />';
	$return .= fn_HtmlEndFieldset();
	
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
	extract($_POST);
/*
	$i = 0;
	$choix = array();
	$choix[0]="";

	// si la variable est présente, on lui affecte une place dans le tableau 'choix[]', qui nous servira ensuite à construire le WHERE de la requête.
	if(isset($type) && !empty($type)) { $choix[$i++] = "`type` = '$type'";}
	if(isset($time) && !empty($time)) { $choix[$i++] = "`time` = '$time'";}
	if(isset($userid) && !empty($userid)) { $choix[$i++] = "`userid` = '$userid'";}


	// on insère les éléments remplis dans une variable $critere, en commençant par la première occurrence, puis on boucle
	$critere = $choix[0];

	for($j=1;$j<$i;$j++) {
	$critere .= " AND ".$choix[$j]." ";
	}

	// enfin on fait la requête si $i >0, ça veut dire qu'il y a des critères
	if($i > 0) { // requete de selection
		$sql = "SELECT * FROM trace WHERE " . $critere . " ORDER BY time desc limit 100";
	}
	else { // si $i = 0, alors l'utilisateur n'a pas saisi de critère, là soit on fait la même requete mais sans "WHERE $critere", soit on lui demande de saisir au moins un critère.
		$sql = "SELECT * FROM trace ORDER BY time desc limit 100";
	}

	//récupération des données
	$rows = $db->runQuery($sql);

	
	if (!$rows) {
	   $return .=  "Impossible d'exécuter la requête ($select) dans la base : ";
	}
	if (count($rows) == 0) {
		$return .= fn_GetTranslation('no_line_result');
	}
	*/	
	$return .= '<table id = "TraceLog"  class="display" cellspacing="0" width="100%" >';
	$return .= '<thead>';
	$return .= '<tr>';
	$return .= '<th></th>';
	$return .= '<th>id</th>';
	$return .= '<th>user_id</th>';
	$return .= '<th>type</th>';
	$return .= '<th>from</th>';
	$return .= '<th>texte</th>';
	$return .= '<th>user time</th>';
	$return .= '</tr>';
	$return .= '</thead>';	
	$return .= '</table>';
	$return .= '<br>'; 
	$return .= '</form>';
	$return .= '</div>';
	
	echo $return;
	
		include($GLOBALS["page_inc_path"] . 'headloadjs.php');
		if (isset($_SESSION['Ajax']) && $_SESSION['Ajax']) {
?>
			<script type="text/javascript"> 
				function updateTrace () {
					$('#TraceLog').DataTable( {
						"ajax": "?app=Ws&page=trace.JSON",
						"processing": true,
						"bAutoWidth": false,
						"columns": [ 
							{ 
								"class":          "details-control",
								"orderable":      false,
								"data":           null,
								"defaultContent": ""
							}, 
							{ "data": "id" },
							{ "data": "username" },
							{ "data": "type" },
							{ "data": "ipfrom" },
							{ "data": "texte" },
							{ "data": "usertime" },
						],
						"order": [[0, 'desc']]
						
					} );
				 
					// Array to track the ids of the details displayed rows
					
					/*
					var detailRows = [];
				 
					$('#TraceLog tbody').on( 'click', 'tr td.details-control', function () {
						var tr = $(this).closest('tr');
						var row = dt.row( tr );
						var idx = $.inArray( tr.attr('id'), detailRows );
				 
						if ( row.child.isShown() ) {
							tr.removeClass( 'details' );
							row.child.hide();
				 
							// Remove from the 'open' array
							detailRows.splice( idx, 1 );
						}
						else {
							tr.addClass( 'details' );
							row.child( format( row.data() ), "displaydetail" ).show();
				 
							// Add to the 'open' array
							if ( idx === -1 ) {
								detailRows.push( tr.attr('id') );
							}
						}
					} );
				 
					// On each draw, loop over the `detailRows` array and show any child rows
					dt.on( 'draw', function () {
						$.each( detailRows, function ( i, id ) {
							$('#'+id+' td.details-control').trigger( 'click' );
						} );
					} );
					*/

				}
				
				
				function format ( d ) {
					return 	'<p>Username: '+d.username+'</p>'+
							'<p>Message: <span id="message'+d.id+'">'+d.texte+'</span></p>'+
							'<button class="btnclipboard" data-clipboard-target="#message'+d.id+'">Copy to clipboard</button>';
				}
				jQuery(document).ready(function() {
					new Clipboard('.btnclipboard');
					// Chargement de la liste déroulante		
					AjaxLoadSelectJson("type", "tracetype=true", false, "" );
					//$("#type").select2({data: <?php echo json_encode($Lusers);?>});
					updateTrace();
					});
			</script>
<?php
		}
?>

