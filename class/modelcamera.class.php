<?php
require_once($GLOBALS["mvc_path"]."top.php");
class typecamera extends top
{
	public $js, $inc, $flux, $image, $autorefresh;
	public function save($list_data = null)
	{
		return parent::save(array("label", "js", "inc", "flux", "image", "autorefresh"));
	}
}
?>