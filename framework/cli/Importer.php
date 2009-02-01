<?php
class Importer
{
	var $fp;
	
	function importer()
	{
	}
	
	function init()
	{
	}
	
	function openFile($filename)
	{
		$this->fp = fopen($filename, 'r');
	}
	
	function readLine()
	{
		trigger_error('virtual function');
	}
	
	function parseLine($line)
	{
		trigger_error('virtual function');
	}
	
	function processLine($lineData)
	{
		trigger_error('virtual function');
	}
	
	function go($filename)
	{
		$this->init();
		$this->openFile($filename);
		
		if($this->skipFirstLine())
		{
			$line = $this->readLine();			
			echo "skipping line: \n";
			print_r($line);
		}
		
		while($line = $this->readLine())
		{
			if($lineData = $this->parseLine($line))
				$this->processLine($lineData);
		}
		$this->finish();
	}
	
	function skipFirstLine()
	{
		return 0;
	}
}