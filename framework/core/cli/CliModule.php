<?php
class CliModule extends ZoopModule
{
	function getClasses()
	{
		return array('CsvImporter', 'Importer', 'CliApplication');
	}
}

