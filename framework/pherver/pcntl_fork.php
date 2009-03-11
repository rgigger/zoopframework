#!/usr/bin/env php
<?php
$pid = pcntl_fork();
if ($pid == -1)
{
	die('could not fork');
}
else if($pid)
{
	echo "I am the parent\n";
	pcntl_wait($status); //Protect against Zombie children
}
else 
{
	echo "I am the child";
	sleep(10);
}
