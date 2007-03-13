<?php
function SqlEchoOn()
{
	global $DefaultDb;
	return $DefaultDb->echoOn();
}

function SqlEchoOff()
{
	global $DefaultDb;
	return $DefaultDb->echoOff();
}

function SqlBeginTransaction()
{
	global $DefaultDb;
	return $DefaultDb->beginTransaction();
}

function SqlCommitTransaction()
{
	global $DefaultDb;
	return $DefaultDb->commitTransaction();
}

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

function SqlFetchSimpleMap($sql, $keyFields, $valueField, $params)
{
	global $DefaultDb;
	return $DefaultDb->fetchSimpleMap($sql, $keyFields, $valueField, $params);
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

function SqlDeleteRows($sql, $params)
{
	global $DefaultDb;
	return $DefaultDb->deleteRows($sql, $params);
}
