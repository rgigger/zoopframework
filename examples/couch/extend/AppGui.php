<?php
class AppGui extends Gui
{
	function fetch($tpl_file, $cache_id = null, $compile_id = null, $display = false)
	{
		$this->assign("TEMPLATE_CONTENT", $tpl_file);
		
		return parent::fetch('layouts/main.tpl', $cache_id, $compile_id, $display);
	}
	
}