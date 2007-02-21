<?php
function SqlFetchCell($sql, $params)
{
	global $DefaultDb;
	return $DefaultDb->fetchCell($sql, $params);
}

function SqlFetchRow($sql, $params)
{
	global $DefaultDb;
	return $DefaultDb->fetchRow($sql, $params);
}

function SqlFetchRows($sql, $params)
{
	global $DefaultDb;
	return $DefaultDb->fetchRows($sql, $params);
}

function SqlInsertRow($sql, $params)
{
	global $DefaultDb;
	return $DefaultDb->insertRow($sql, $params);
}

function SqlUpdateRow($sql, $params)
{
	global $DefaultDb;
	return $DefaultDb->updateRow($sql, $params);
}

function SqlSelsertRow($tableName, $fieldNames, $conditions, $defaults = NULL, $lock = 0)
{
	global $DefaultDb;
	return $DefaultDb->selsertRow($tableName, $fieldNames, $conditions, $defaults, $lock);
}

function SqlUpsertRow($tableName, $conditions, $values)
{
	global $DefaultDb;
	return $DefaultDb->upsertRow($tableName, $conditions, $values);
}