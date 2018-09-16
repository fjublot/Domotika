<?php 
	function fn_HtmlStartPanel($title, $subtitle, $iconclass="", $numero="", $addclass="") {
		$icon="";
		$badge='';
		if ($numero == '')
			$numero = ucfirst(fn_GetTranslation('new'));
		if ($numero != "none")
			$badge='<span class="badge bg-red">'.$numero.'</span>';
		
		if ($iconclass !="")
			$icon='<i class="fa ' . fn_GetTranslation('fa-'.$iconclass) . '"></i>'.'&nbsp;';
		$return  = '	<div class="x_panel ' . $addclass . '">'.PHP_EOL;
		$return .= '		<div class="x_title">'.PHP_EOL;
		$return .= '			<h2>'.$icon.$title.'&nbsp;<small>'.$subtitle.'</small>&nbsp;'.$badge.'</h2>'.PHP_EOL;
		/*
		$return .= '			<ul class="nav navbar-right panel_toolbox">'.PHP_EOL;
		$return .= '				<li>'.PHP_EOL;
		$return .= '					<a href="#" class="collapse-link">'.PHP_EOL;
		$return .= '						<i class="fa fa-chevron-up"></i>'.PHP_EOL;
		$return .= '					</a>'.PHP_EOL;
		$return .= '				</li>'.PHP_EOL;
		$return .= '				<li class="dropdown">'.PHP_EOL;
		$return .= '					<a class="dropdown-toggle" aria-expanded="false" role="button" data-toggle="dropdown" href="#">'.PHP_EOL;
		$return .= '						<i class="fa fa-wrench"></i>'.PHP_EOL;
		$return .= '					</a>'.PHP_EOL;
		$return .= '				</li>'.PHP_EOL;
		$return .= '				<li>'.PHP_EOL;
		$return .= '					<a href="#" class="close-link">'.PHP_EOL;
		$return .= '						<i class="fa fa-close"></i>'.PHP_EOL;
		$return .= '					</a>'.PHP_EOL;
		$return .= '				</li>'.PHP_EOL;
		$return .= '			</ul>'.PHP_EOL;
		*/
		$return .= '			<div class="clearfix"></div>'.PHP_EOL;
		$return .= '		</div>'.PHP_EOL;
		$return .= '	<div class="x_content">'.PHP_EOL;
		return $return;
	}
?>