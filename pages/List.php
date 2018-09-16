<!-- page content -->
<?php 
//		echo '<!--' . $currenturl .'-->';
		$titlecomp="";
		if ($filtre_carteid!="") {
		$xpath  = '//cartes/carte[@numero="'.$filtre_carteid.'"]';
		$labelcarteid = fn_GetByXpath($xpath, 'bal', 'label');
		$titlecomp = " - ".fn_GetTranslation("carte")." ".$labelcarteid."(".$filtre_carteid.")";
		}
?>

	<div class="x_panel">
		<div class="x_title">
			<h2><?php echo ucfirst(fn_GetTranslation($class).'s'.$titlecomp);?></h2>
			<div class="clearfix"></div>
		</div>	

			<!-- Zone de search -->
			<!-- /Zone de search -->				
		<div class="x_content">
					<div class="row hidden-xs">
						<div class="col-md-9 col-sm-12 col-xs-12" style="text-align:center;">
							<ul class="pagination pagination-split">
							    <li><a class="filterbutton" href="#" data-beginletter="">ALL</a></li>
								<?php 
									$lettres=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'); 
									foreach ( $lettres as $lettre ) {
										echo '<li><a class="filterbutton" href="#" data-beginletter="'.$lettre.'">'.$lettre.'</a></li>';
									}
								?>
								
							</ul>
						</div>
						<div class="title_right">
							<div class="col-md-3 col-sm-12 col-xs-12 form-group pull-right top_search">
								<div class="input-group">
									<span class="input-group-btn">
										<button id = "clearsearchbutton" class="btn btn-danger" type="button">X</button>
									</span>
									<input id="searchstring" type="text" class="form-control" placeholder="Search for...">
									<span class="input-group-btn">
										<button id = "searchbutton" class="btn btn-default" type="button">Go!</button>
									</span>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<?php
							if ($class!="" && count($GLOBALS["config"]->{$class . "s"}->{$class})>0) {
								$xpath = '//'.$class.'s/'.$class;
								$collections = $GLOBALS["config"]->xpath($xpath);
								usort($collections, 'fn_SortByNumero');
								foreach($collections as $info) {
									if (isset($_REQUEST["filtre_numero"]) && !preg_match("/" . $_REQUEST["filtre_numero"] . "/", $info->attributes()->numero))
										continue;
									if (isset($_REQUEST["filtre_carteid"]) && (!isset($info->carteid) || $_REQUEST["filtre_carteid"] != (string)$info->carteid))
										continue;
									if ($class=="carte") {
										if (class_exists(fn_GetModel($class, $info->attributes()->numero)))
											$model = fn_GetModel($class, $info->attributes()->numero);
										else
											$model = $class;
										}
									else {
										$model = $class;
									}
									
									$current = new $model($info->attributes()->numero, $info);
									$current->disp_list();
								}
						
							}
						if ($addnew!='') {	
							$carteatt="";
							if ($filtre_carteid !="")
								$carteatt="&filtre_carteid=".$filtre_carteid;
						?>
							<a href="?app=Mn&page=Add&class=<?php echo $class.$carteatt;?>" class="listaddicon <?php echo $GLOBALS["classDispList"];?>" data-original-title="<?php echo ucfirst(fn_GetTranslation('add_element'));?>">
								<div class="well profile_view">
									<div class="col-sm-12 center">
										<img src="./images/button-circle-add-512.png"/>;
									</div>';
								</div>';
							</a>';
						<?php
						}
						?>
							
					</div>
				</div>

	</div>
<!-- /page content -->
<?php
		include($GLOBALS["page_inc_path"] . 'headloadjs.php');
?>
<script type="text/javascript"> 
    //Override de la fonction "contains" pour permettre une recherche case-insensitive
	$('.listaddicon').tooltip({
		trigger: 'hover',
		placement: 'top',
		animate: true,
		delay: 500,
		container: '.listaddicon'
	});	
    jQuery.expr[':'].contains = jQuery.expr.createPseudo(function(arg) {
        return function( elem ) {
			return jQuery(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
		};
    });
	
	$('#searchbutton').on( "click", function() {
		$('.searchable').parents('a').addClass("hidden").removeClass("fadeInDown"); 
	    $('.searchable:contains('+$('#searchstring').val().toUpperCase()+')').parents('a').removeClass("hidden").addClass("fadeInDown"); 
		});
	$('.filterbutton').on( "click", function() {
		$('.filterable').parents('a').addClass("hidden").removeClass("fadeInDown"); 
	    $('.filterable[data-beginletter="'+$(this).data("beginletter")+'"]').parents('a').removeClass("hidden").addClass("fadeInDown");
		if ($(this).data("beginletter")=="") 
			$('.filterable').parents('a').removeClass("hidden").addClass("fadeInDown");		
	});
	$('#clearsearchbutton').on( "click", function() {
		$('#searchstring').val("");
		$('#searchbutton').trigger("click");
	});
</script>	
