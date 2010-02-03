<?php
class DbPgResult extends DbResultSet
{
	private $cur;
	private $max;
	
	function __construct($link, $res)
	{
		parent::__construct($link, $res);
		$this->cur = 0;
		$this->max = pg_num_rows($this->res) - 1;
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
		return pg_fetch_assoc($this->res, $this->cur);
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
		return pg_fetch_assoc($this->res, $this->cur);
	}
	
	function valid()
	{
		if($this->cur > $this->max)
			return false;
		
		return true;
	}
	
	function affectedRows()
	{
		return pg_affected_rows($this->res);
	}
}
