<?php 
	function fn_HtmlStartForm($title="", $iconclass="", $numero="") {
		$badge='';
		if ($numero == '')
			$numero = ucfirst(fn_GetTranslation('new'));
		if ($numero != "none")
			$badge='<span class="badge bg-red">'.$numero.'</span>';
		$return  = '<div class="col-md-12 col-sm-12 col-xs-12">';
		$return .= '	<div class="x_panel">';
		$return .= '		<div class="x_title">';
		if ($iconclass !="")
			$return .= '			<h2><i class="fa ' . fn_GetTranslation('fa-'.$iconclass) . '"></i>&nbsp;'.fn_GetTranslation($iconclass).'&nbsp;'.$badge.'<small>'.$title.'</small></h2>';
		else
			$return .= '			<h2>'.$title.$badge.'</h2>';
		$return .= '			<ul class="nav navbar-right panel_toolbox">';
		$return .= '				<li>';
		$return .= '					<a href="#">';
		$return .= '						<i class="fa fa-chevron-up"></i>';
		$return .= '					</a>';
		$return .= '				</li>';
		$return .= '				<li class="dropdown">';
		$return .= '					<a class="dropdown-toggle" aria-expanded="false" role="button" data-toggle="dropdown" href="#">';
		$return .= '						<i class="fa fa-wrench"></i>';
		$return .= '					</a>';
		$return .= '				</li>';
		$return .= '				<li>';
		$return .= '					<a href="#">';
		$return .= '						<i class="fa fa-close"></i>';
		$return .= '					</a>';
		$return .= '				</li>';
		$return .= '			</ul>';
		$return .= '			<div class="clearfix"></div>';
		$return .= '		</div>';

		return $return;
	}
?>