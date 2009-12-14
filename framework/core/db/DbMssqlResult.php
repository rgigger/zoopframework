<?php
class DbMssqlResult extends DbResultSet
{
	var $cur;
	var $max;
	
	function __construct($link, $res)
	{
		parent::__construct($link, $res);
		$this->cur = 0;
		$this->max = mssql_num_rows($this->res) - 1;
	}
	
	function numRows()
	{
		return $this->max + 1;
	}
	
	function rewind()
	{
		$this->cur = 0;
	}
	
	function current()
	{
		if($this->max == -1)
			return false;
		mssql_data_seek($this->res, $this->cur);
		return mssql_fetch_assoc($this->res);
	}
	
	function key()
	{
		return $this->cur;
	}
	
	function next()
	{
		$this->cur++;
		if($this->cur > $this->max)
			return false;
		mssql_data_seek($this->res, $this->cur);
		return mssql_fetch_assoc($this->res);
	}
	
	function valid()
	{
		if($this->cur > $this->max)
			return false;
		
		return true;
	}
	
	function affectedRows()
	{
        $result = mssql_query("select @@rowcount as rows", $this->link);
        $rows = mssql_fetch_assoc($result);
        return $rows['rows'];
	}
}
