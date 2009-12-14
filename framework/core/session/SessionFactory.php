<?php
class SessionFactory
{
	static function getEngine($params)
	{
		return new SessionDb($params);
	}
	
}
