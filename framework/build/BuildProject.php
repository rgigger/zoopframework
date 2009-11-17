<?php
abstract class BuildProject
{
	public function build($target)
	{
		$method = "target$target";
		$this->$method();
	}
}
