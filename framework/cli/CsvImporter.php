<?php
class CsvImporter extends Importer
{
	var $separator;
	
	function __construct($separator = ',')
	{
		$this->importer();
		$this->separator = $separator;
	}
	
	function readLine()
	{
		$array = fgetcsv($this->fp, 1000, $this->separator);
		return $array;
	}
}
