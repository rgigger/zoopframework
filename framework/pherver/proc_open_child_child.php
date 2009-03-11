#!/usr/bin/env php
<?php
define('zoop_dir', dirname(__file__) . '/../../framework');
define('app_dir', dirname(__file__));
include(zoop_dir . '/Zoop.php');
Zoop::loadLib('app');

// echo "I am the child\n";
$stdin = STDIN;
// $stdin = fopen('php://stdin','rb');
// $stdout = fopen('php://stdout','wb');

while(true)
{
//	$command = stream_get_contents($stdin);
	// fwrite ($stdout , "the command is $command");
	// echo "the command is $command";
	
	$message = trim(fgets($stdin, 4096));
	
	static $i = 1;
	echo "$message : child $i\n";
	// flush();
	$i += 1;
}

// fclose($stdin);
// fclose($stdout);

exit(0);