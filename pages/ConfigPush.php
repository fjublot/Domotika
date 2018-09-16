<?php
require_once($GLOBALS["page_inc_path"] . 'function.php');
include($GLOBALS["page_inc_path"] . 'head.php');
include($GLOBALS["page_inc_path"] . 'topbar.php');
foreach($GLOBALS["config"]->cartes->carte as $info)
{
	if ( isset($_REQUEST["filtre_numero"]) && ! preg_match("/".$_REQUEST["filtre_numero"]."/", $info->attributes()->numero) )
		continue;
	$model = fn_GetModel($info->attributes()->numero);
	$current = new $model($info->attributes()->numero, $info);
	$display_errors = ini_get("display_errors");
	ini_set("display_errors", "off");
	$current->config_push();
	ini_set("display_errors", $display_errors);
}
if ( isset($_REQUEST["HTTP_REFERER"]) && isset($GLOBALS["config"]->general->debug) && $GLOBALS["config"]->general->debug == 1 )
{
	?>
	<div class="center">
		<div id="composebuttons" class="formbuttons">
			<input class="button mainaction" type="button" name="BT_Continuer" OnClick="javascript:window.location='<?php echo $_REQUEST["HTTP_REFERER"]; ?>';" value="<?php echo fn_GetTranslation('continue'); ?>" >
		</div>
	</div>
	<?php
}
include($GLOBALS["page_inc_path"] . 'foot.php');