<?php
class PherverModule extends ZoopModule
{
	function getClasses()
	{
		return array('Pherver', 'ChatServer', 'HttpServer');
	}
}
