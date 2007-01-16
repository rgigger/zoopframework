<?php
class TodoListParser
{
	var $file;
	var $root;
	var $stack;
	var $lookAheadLine;
	
	function parseFile($filename)
	{
		$this->file = fopen($filename, "r");
		
		$this->root = new TodoListItem($dummy = NULL, '*', '');
		$this->stack = array();
		$this->stack[0] = &$this->root;
		
		while(!$this->done())
		{
			$nextItem = $this->getNextItem();
		}
		
		fclose($this->file);
		
		return $this->root;
	}
	
	function getNextItem()
	{
		$line = $this->getNextLine();
		
		$parsed = $this->parseFirstLine($line);
		$parent = &$this->stack[$parsed['tabLevel']];
		
		$thisItem = new TodoListItem($parent, $parsed['status'], $parsed['line']);
		
		$this->stack[$parsed['tabLevel'] + 1] = &$thisItem;
	}
	
	function parseFirstLine($line)
	{
		ereg("([\t]*)([+-/])(.*)", $line, $regs);
		$return['tabLevel'] = strlen($regs[1]);
		$return['status'] = $regs[2];
		$return['line'] = $regs[3];
		return $return;
	}
	
	function getNextLine($value='')
	{
		if($this->lookAheadLine)
		{
			$this->lookAheadLine = NULL;
			return $this->lookAheadLine;
		}
		
		return fgets($this->file, 4096);
	}
	
	function done()
	{
		return feof($this->file);
	}
}