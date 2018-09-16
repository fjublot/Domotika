<?php
	echo fn_HtmlStartPanel("Alerts", ucfirst(fn_GetTranslation("alerts")), "alerts", "none");
	//$return .='
?>
				<table id="alert-grid"  cellpadding="0" cellspacing="0" border="0" class="display" width="100%">
					<thead>
						<tr>
							<th>id</th>
							<th>user_id</th>
							<th>type</th>
							<th>from</th>
							<th>texte</th>
							<th>user time</th>
						</tr>
					</thead>
					<thead>
						<tr>
							<td></td>
							<td><input type="text" data-column="1" class="search-input-text"></td>
							<td><input type="text" data-column="2" class="search-input-text"></td>
							<td><input type="text" data-column="3" class="search-input-text"></td>
							<td><input type="text" data-column="4" class="search-input-text"></td>
							<td><input type="text" data-column="5" class="search-input-text"></td>
							<!--<td>
								<select data-column="2"  class="search-input-select">
									<option value="">(Select a range)</option>
									<option value="19-30">19 - 30</option>
									<option value="31-66">31 - 66</option>
								</select>
							</td>
							-->
						</tr>
					</thead>
				</table>
		</div>
<?php	
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

	include($GLOBALS["page_inc_path"] . 'headloadjs.php');
?>
			<script type="text/javascript"> 
				function format ( d ) {
					return 	'<p>Username: '+d.username+'</p>'+
							'<p>Message: <span id="message'+d.id+'">'+d.texte+'</span></p>'+
							'<button class="btnclipboard" data-clipboard-target="#message'+d.id+'">Copy to clipboard</button>';
				}
				jQuery(document).ready(function() {
					new Clipboard('.btnclipboard');
					// Chargement de la liste déroulante		
					AjaxLoadSelectJson("type", "alerttype=true", false, "" );
					//$("#type").select2({data: <?php echo json_encode($Lusers);?>});
					
					var dataTable = $('#alert-grid').DataTable( {
						"processing": true,
						"serverSide": true,
						"autowidth":true,
						"order": [[ 0, "desc" ]],
						"iDisplayLength": 20,
						"lengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "All"]],
						"ajax":{
							url :"?app=Ws&page=alert-grid-data", // json datasource
							type: "post",  // method  , by default get
							error: function(){  // error handling
								$(".alert-grid-error").html("");
								$("#alert-grid").append('<tbody class="alert-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
								$("#alert-grid_processing").css("display","none");
								
							}
						}
					} );
					$("#alert-grid_filter").css("display","none");  // hiding global search box
					$('.search-input-text').on( 'keyup click', function () {   // for text boxes
						var i =$(this).attr('data-column');  // getting column index
						var v =$(this).val();  // getting search input value
						dataTable.columns(i).search(v).draw();
					} );
					$('.search-input-select').on( 'change', function () {   // for select box
						var i =$(this).attr('data-column');  
						var v =$(this).val();  
						dataTable.columns(i).search(v).draw();
					} );
				} );
			</script>

