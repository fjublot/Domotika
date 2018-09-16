<?php
	$return  = fn_HtmlStartPanel("Histo", ucfirst(fn_GetTranslation("histo")), "histo", "none");
	$return .= '<form name="ajaxform" id="ajaxform" class="form-horizontal form-label-left" novalidate>';
	$return .= fn_HtmlSelectField('element', 'element', 'element');
	echo $return;
	$list_search_info = array('relai', 'razdevice', 'btn', 'an', 'cnt', 'variable', 'vartxt');
?>
	<table id="histo-grid"  cellpadding="0" cellspacing="0" border="0" class="display" width="100%">
		<thead>
			<tr>
				<th>time</th>
				<th>value</th>
			</tr>
		</thead>
		<thead>
			<tr>
				<td><input type="text" data-column="0" class="search-input-text"></td>
				<td><input type="text" data-column="1" class="search-input-text"></td>
			</tr>
		</thead>
	</table>
	</form>
	</div>
<?php	
	
	include($GLOBALS["page_inc_path"] . 'headloadjs.php');
?>
		<script type="text/javascript">
			jQuery(document).ready(function () { 
				AjaxLoadSelectJson("elem", "class=all", false );
				/*	var dataTable = $('#histo-grid').DataTable( {
						"processing": true,
						"serverSide": true,
						"autowidth":true,
						"order": [[ 0, "desc" ]],
						"iDisplayLength": 20,
						"lengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "All"]],
						"ajax":{
							url :"?app=Ws&page=histo-grid-data", // json datasource
							type: "post",  // method  , by default get
							error: function(){  // error handling
								$(".trace-grid-error").html("");
								$("#trace-grid").append('<tbody class="trace-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
								$("#trace-grid_processing").css("display","none");
								
							}
						}
					} );
					$("#trace-grid_filter").css("display","none");  // hiding global search box
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
				*/
				} );
			</script>

