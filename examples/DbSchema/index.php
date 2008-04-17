<?php
include('config.php');
include(zoop_dir . '/Zoop.php');
Zoop::loadLib('app');
Zoop::loadLib('db');

$schema = new DbSchema(DbModule::getDefaultConnection());
foreach($schema->tables as $thisTable)
{
	// echo_r($thisTable);
	echo "<strong>Table name = {$thisTable->name}</strong><br>";
	foreach($thisTable->fields as $thisField)
	{
		echo "field name = {$thisField->name} field type = {$thisField->type}<br>";
		// echo_r($thisField);
	}
}

