<?php
class DbPdoResult
{
	var $res;
	var $cur;
	var $max;
	
	function DbPdoResult($res)
	{
		$this->res = $res;
		$this->cur = 0;
		$this->max = $this->res->rowCount();
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
		return $this->res->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_ABS, $this->cur);
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
		return $this->res->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_ABS, $this->cur);
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