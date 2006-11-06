<?php
class Application
{
	function Application()
	{
		session_start();
	}
	
	function run()
	{
		trigger_error('run does nothing for Application');
	}
}