<?php
	echo fn_HtmlStartPanel(ucfirst(fn_GetTranslation("historical")),'' , "histo", "none");
	echo fn_HtmlSelectField('filter', 'filter', 'filter');
?>
				<table id="histo-grid"  cellpadding="0" cellspacing="0" border="0" class="display" width="100%">
					<thead>
						<tr>
							<th>Date</th>
							<th>Etat</th>
							<th>Etat texte</th>
						</tr>
					</thead>
					<thead>
						<tr>
							<td><input type="text" data-column="0" class="search-input-text"></td>
							<td><input type="text" data-column="1" class="search-input-text"></td>
							<td><input type="text" data-column="2" class="search-input-text"></td>
						</tr>
					</thead>
				</table>
		</div>
<?php	
	include($GLOBALS["page_inc_path"] . 'headloadjs.php');
?>
			<script type="text/javascript"> 
				jQuery(document).ready(function() {
					// Chargement de la liste déroulante		
					AjaxLoadSelectJson("filter", "histotype=true", false, "" );	
					$('#filter').on( 'change', function () {   // for select box
						if ($(this).val() != null) {
							var select = $(this).val().split('|');
							var dataTable = $('#histo-grid').DataTable( {
								"destroy": true,
								"processing": true,
								"serverSide": true,
								"autowidth":true,
								"order": [[ 0, "desc" ]],
								"iDisplayLength": 20,
								"lengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "All"]],
								"ajax":{
									url :"?app=Ws&class="+select[0]+"&numero="+select[1]+"&page=histo-grid-data", // json datasource
									type: "post",  // method  , by default get
									error: function(){  // error handlinghisto
										$(".histo-grid-error").html("");
										$("#histo-grid").append('<tbody class="-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
										$("#histo-grid_processing").css("display","none");
										
									}
								}
							} );
						}
					} );
					
					$("#histo-grid_filter").css("display","none");  // hiding global search box
					
					$('.search-input-text').on( 'keyup click', function () {   // for text boxes
						var i =$(this).attr('data-column');  // getting column index
						var v =$(this).val();  // getting search input value
						var dataTable = $('#histo-grid').DataTable();
						dataTable.columns(i).search(v).draw();
					} );
					$('.search-input-select').on( 'change', function () {   // for select box
						var i =$(this).attr('data-column');  
						var v =$(this).val();  
						var dataTable = $('#histo-grid').DataTable();
						dataTable.columns(i).search(v).draw();
					} );
				} );
			</script>

