<?php
class DbResultSet
{
	private $res;
	private $link;
	
	function __construct($link, $res)
	{
		$this->link = $link;
		$this->res = $res;
	}
}