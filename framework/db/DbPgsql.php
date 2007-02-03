<?php
class DbPgsql extends DbConnection
{
	function escapeString($string)
	{
		return "'$string'";
	}
	
}