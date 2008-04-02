<?php
/**
 * Turn on echoing of sql statements
 *
 * @return nothing
 */
function SqlEchoOn()
{
	return DbModule::getDefaultConnection()->echoOn();
}

/**
 * Turn off echoing of sql statements
 *
 * @return nothing
 */
function SqlEchoOff()
{
	return DbModule::getDefaultConnection()->echoOff();
}

/**
 * Begin a transaction (not all database engines support transactions)
 *
 * @return nothing
 */
function SqlBeginTransaction()
{
	return DbModule::getDefaultConnection()->beginTransaction();
}

/**
 * Commit a transaction (not all database engines support transactions)
 *
 * @return nothing
 */
function SqlCommitTransaction()
{
	return DbModule::getDefaultConnection()->commitTransaction();
}

/**
 * Executes a database query.  $params must be a $key => $value array of values to substitute into $sql 
 * Returns a DbResultSet object
 * If you are not passing parameters in, $params should be an empty array()
 * 
 * @param string $sql
 * @param array($key=>$value) $params
 * @return DbResultSet
 */
function SqlQuery($sql, $params)
{
	return DbModule::getDefaultConnection()->query($sql, $params);
}

function SqlGetSchema($name = 'public')
{
	return DbModule::getDefaultConnection()->getSchema($name);
}

function SqlAlterSchema($sql)
{
	return DbModule::getDefaultConnection()->alterSchema($sql);
}

function SqlFetchCell($sql, $params)
{
	return DbModule::getDefaultConnection()->fetchCell($sql, $params);
}

function SqlFetchRow($sql, $params)
{
	return DbModule::getDefaultConnection()->fetchRow($sql, $params);
}

function SqlFetchColumn($sql, $params)
{
	return DbModule::getDefaultConnection()->fetchColumn($sql, $params);
}

function SqlFetchRows($sql, $params)
{
	return DbModule::getDefaultConnection()->fetchRows($sql, $params);
}

function SqlFetchMap($sql, $mapFields, $params)
{
	return DbModule::getDefaultConnection()->fetchMap($sql, $mapFields, $params);
}

function SqlFetchSimpleMap($sql, $keyFields, $valueField, $params)
{
	return DbModule::getDefaultConnection()->fetchSimpleMap($sql, $keyFields, $valueField, $params);
}

function SqlInsertArray($tableName, $values)
{
	return DbModule::getDefaultConnection()->insertArray($tableName, $fieldInfo);
}

function SqlModifyRow($sql, $params)
{
	return DbModule::getDefaultConnection()->modifyRow($sql, $params);
}

function SqlModifyRowValues($tableName, $values)
{
	return DbModule::getDefaultConnection()->modifyRowValues($tableName, $values);
}

function SqlInsertRow($sql, $params)
{
	return DbModule::getDefaultConnection()->insertRow($sql, $params);
}

function SqlInsertRowValues($tableName, $values)
{
	return DbModule::getDefaultConnection()->insertRowValues($tableName, $values);
}

function SqlUpdateRow($sql, $params)
{
	return DbModule::getDefaultConnection()->updateRow($sql, $params);
}

function SqlSelsertRow($tableName, $fieldNames, $conditions, $defaults = NULL, $lock = 0)
{
	return DbModule::getDefaultConnection()->selsertRow($tableName, $fieldNames, $conditions, $defaults, $lock);
}

function SqlUpsertRow($tableName, $conditions, $values)
{
	return DbModule::getDefaultConnection()->upsertRow($tableName, $conditions, $values);
}

function SqlDeleteRows($sql, $params)
{
	return DbModule::getDefaultConnection()->deleteRows($sql, $params);
}
