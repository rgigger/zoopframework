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
 */
function SqlEchoOff()
{
	return DbModule::getDefaultConnection()->echoOff();
}

/**
 * Begin a transaction (not all database engines support transactions)
 *
 */
function SqlBeginTransaction()
{
	return DbModule::getDefaultConnection()->beginTransaction();
}

/**
 * Commit a transaction (not all database engines support transactions)
 *
 */
function SqlCommitTransaction()
{
	return DbModule::getDefaultConnection()->commitTransaction();
}

/**
 * Executes a database query.  $params must be a $key => $value array of values to substitute into $sql
 *
 * @param string $sql SQL query with parameters in the format ":variablename" or ":variablename:datatype"
 * @param array($key=>$value) $params ($key => value) array of parameters to substitute into the SQL query. If you are not passing parameters in, params should be an empty array()
 * @return DbResultSet DbResultSet object
 */
function SqlQuery($sql, $params)
{
	return DbModule::getDefaultConnection()->query($sql, $params);
}

/**
 * Returns a DbSchema object
 * This function returns a DbSchema object that can be used to query the structure of the database in a DOM like fashion.
 *
 * @return DbSchema
 */
function SqlGetSchema()
{
	return DbModule::getDefaultConnection()->getSchema();
}

/**
 * Executes a SQL statement to alter the schema
 * Unlike SqlQuery, this doesn't return a DbResultSet or accept parameters
 *
 * @param string $sql SQL query to execute
 * @returns nothing
 */
function SqlAlterSchema($sql)
{
	return DbModule::getDefaultConnection()->alterSchema($sql);
}

/**
 * Executes the given query and returns the value of the first cell in the first row of the resultset.
 *
 * An error will occur if the query returns more than one row.
 *
 * @param string $sql SQL query with parameters in the format ":variablename" or ":variablename:datatype"
 * @param array($key=>$value) $params ($key => value) array of parameters to substitute into the SQL query. If you are not passing parameters in, params should be an empty array()
 * @return mixed value of cell or false if no record returned
 */
function SqlFetchCell($sql, $params)
{
	return DbModule::getDefaultConnection()->fetchCell($sql, $params);
}

/**
 * Executes the given query and returns the first row of the resultset.
 *
 * An error will occur if the query returns more than one row.
 *
 * @param string $sql SQL query with parameters in the format ":variablename" or ":variablename:datatype"
 * @param array($key=>$value) $params ($key => value) array of parameters to substitute into the SQL query. If you are not passing parameters in, params should be an empty array()
 * @return array row data or false if no record returned
 */
function SqlFetchRow($sql, $params)
{
	return DbModule::getDefaultConnection()->fetchRow($sql, $params);
}

/**
 * Returns an array containing the values from the first column of each row returned by the passed-in query
 *
 * @param string $sql SQL query with parameters in the format ":variablename" or ":variablename:datatype"
 * @param array($key=>$value) $params ($key => value) array of parameters to substitute into the SQL query. If you are not passing parameters in, params should be an empty array()
 * @return unknown
 */
function SqlFetchColumn($sql, $params)
{
	return DbModule::getDefaultConnection()->fetchColumn($sql, $params);
}

/**
 * Returns an array of all rows returned from the given SQL statement
 *
 * @param string $sql SQL query with parameters in the format ":variablename" or ":variablename:datatype"
 * @param array($key=>$value) $params ($key => value) array of parameters to substitute into the SQL query. If you are not passing parameters in, params should be an empty array()
 * @return unknown
 */
function SqlFetchRows($sql, $params)
{
	return DbModule::getDefaultConnection()->fetchRows($sql, $params);
}

/**
 * Returns a nested array, grouped by the fields (or field) listed in $mapFields
 * For example, if mapFields = array("person_id", "book_id"), and the resultset returns
 * a list of all the chapters of all the books of all the people, this will group the
 * records by person and by book, keeping each row in an array under
 * $var[$person_id][$book_id]
 *
 * @param string $sql SQL query with parameters in the format ":variablename" or ":variablename:datatype"
 * @param array $mapFields array of fields to group the results by
 * @param array($key=>$value) $params ($key => value) array of parameters to substitute into the SQL query. If you are not passing parameters in, params should be an empty array()
 * @return associative array structure grouped by the values in $mapFields
 */
function SqlFetchMap($sql, $mapFields, $params)
{
	return DbModule::getDefaultConnection()->fetchMap($sql, $mapFields, $params);
}

/**
 * Creates a simple nested array structure grouping the values of the $valueField column by the values of the columns specified in the $keyFields array.
 *
 * For example, if your query returns a list of books and you'd like to group the titles by subject and isbn number, let $keyFields = array("subject", "isbn") and $valueField = "title".
 * The format thus created will be $var[$subject][$isbn] = $title;
 *
 * @param string $sql SQL query with parameters in the format ":variablename" or ":variablename:datatype"
 * @param array $keyFields array of fields to group the results by
 * @param array $valueField name of the field containing the value to be grouped
 * @param array($key=>$value) $params ($key => value) array of parameters to substitute into the SQL query. If you are not passing parameters in, params should be an empty array()
 * @return associative array structure grouped by the values in $mapFields
 */
function SqlFetchSimpleMap($sql, $keyFields, $valueField, $params)
{
	return DbModule::getDefaultConnection()->fetchSimpleMap($sql, $keyFields, $valueField, $params);
}

/**
 * Enter description here...
 *
 * @param string $tableName Table into which we will insert the data
 * @param array $values ($key => $value) array in the format ($fieldName => $fieldValue)
 * @param boolean $serial True if the last inserted ID should be returned
 * @return mixed ID of the inserted row or false if $serial == false
 */
function SqlInsertArray($tableName, $values, $serial = true)
{
	return DbModule::getDefaultConnection()->insertArray($tableName, $values, $serial);
}

/**
 * Executes an update statement on the database (identical to SqlUpdateRow)
 *
 * Unlike SqlQuery, this method does not return a DbResultSet objects
 *
 * @param string $sql SQL query with parameters in the format ":variablename" or ":variablename:datatype"
 * @param array($key=>$value) $params ($key => value) array of parameters to substitute into the SQL query. If you are not passing parameters in, params should be an empty array()
 * @return number Number of affected rows (not all database engines support this)
 */
function SqlModifyRow($sql, $params)
{
	// Is this supposed to work different from SqlUpdateRow?
	//return DbModule::getDefaultConnection()->modifyRow($sql, $params);
	return SqlUpdateRow($sql, $params);
}

/**
 * This function is not yet implimented
 *
 * @param string $sql SQL query with parameters in the format ":variablename" or ":variablename:datatype"
 * @param array($key=>$value) $params ($key => value) array of parameters to substitute into the SQL query. If you are not passing parameters in, params should be an empty array()
 * @return unknown
 */
function SqlModifyRowValues($tableName, $values)
{
	return DbModule::getDefaultConnection()->modifyRowValues($tableName, $values);
}

/**
 * Execute an insert statement
 *
 * Unlike SqlQuery, this method does not return a DbResultSet
 *
 * @param string $sql SQL query with parameters in the format ":variablename" or ":variablename:datatype"
 * @param array($key=>$value) $params ($key => value) array of parameters to substitute into the SQL query. If you are not passing parameters in, params should be an empty array()
 * @return number Number of affected rows (not all database engines support this)
 */
function SqlInsertRow($sql, $params)
{
	return DbModule::getDefaultConnection()->insertRow($sql, $params);
}

function SqlInsertRows($sql, $params)
{
	return DbModule::getDefaultConnection()->insertRows($sql, $params);
}

/**
 * This function has not yet been implemented
 *
 * @param string $sql SQL query with parameters in the format ":variablename" or ":variablename:datatype"
 * @param array($key=>$value) $params ($key => value) array of parameters to substitute into the SQL query. If you are not passing parameters in, params should be an empty array()
 * @return unknown
 */
function SqlInsertRowValues($tableName, $values)
{
	return DbModule::getDefaultConnection()->insertRowValues($tableName, $values);
}

/**
 * Executes an update statement on the database
 *
 * Unlike the method query(), this method does not return a DbResultSet objects
 *
 * @param string $sql SQL query with parameters in the format ":variablename" or ":variablename:datatype"
 * @param array($key=>$value) $params ($key => value) array of parameters to substitute into the SQL query. If you are not passing parameters in, params should be an empty array()
 * @return unknown
 */
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
