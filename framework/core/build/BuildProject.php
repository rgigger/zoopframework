<?php
abstract class BuildProject extends Object
{
	public function build($target)
	{
		$method = "target$target";
		$this->$method();
	}
}
