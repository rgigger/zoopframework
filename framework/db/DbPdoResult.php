<?php
class DbPdoResult
{
	var $res;
	var $cur;
	var $max;
	private $rows;
	
	function DbPdoResult($res)
	{
		$this->res = $res;
		$this->cur = 0;
//		EchoBacktrace();
		if(!$this->res)
			$this->rows = array();
		else
			$this->rows = $this->res->fetchAll();
		$this->max = count($this->rows) - 1;
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
		return $this->rows[$this->cur];
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
		return $this->rows[$this->cur];
	}
	
	function valid()
	{
		if($this->cur > $this->max)
			return false;
		
		return true;
	}
	
	function affectedRows()
	{
		if(!$this->res)
			return 0;
		return $this->res->rowCount();
	}
}
