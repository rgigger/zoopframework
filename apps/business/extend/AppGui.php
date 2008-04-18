<?php
class AppGui extends Gui
{
	private $layout = 'main';
	
	public function setLayout($layout)
	{
		$this->layout = $layout;
	}
	
	function fetch($tpl_file, $cache_id = null, $compile_id = null, $display = false)
	{
		$this->assign("TEMPLATE_CONTENT", $tpl_file);
		return parent::fetch('layouts/' . $this->layout . '.tpl', $cache_id, $compile_id, $display);
	}
}
