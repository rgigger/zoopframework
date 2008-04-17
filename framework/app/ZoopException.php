<?php
class ZoopException extends Exception
{ 
	public function setFile($file)
	{
		$this->file = $file;
	}
	
	public function setLine($line)
	{
		$this->line = $line;
	}
}
