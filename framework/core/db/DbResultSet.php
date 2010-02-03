<?php
class DbResultSet
{
	protected $res;
	protected $link;
	
	function __construct($link, $res)
	{
		$this->link = $link;
		$this->res = $res;
	}
}