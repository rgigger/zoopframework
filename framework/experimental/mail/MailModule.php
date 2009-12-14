<?php
class MailModule extends ZoopModule
{
	private static $connections = array();
	
	function getClasses()
	{
		return array('Mail', 'Mailer', 'Message');
	}
}
