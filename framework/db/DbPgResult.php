<?php
class DbPgResult
{
	var $res;
	var $cur;
	var $max;
	
	function DbPgResult($res)
	{
		$this->res = $res;
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
	
}