#!/usr/bin/env php
<?php
define('zoop_dir', dirname(__file__) . '/../../framework');
define('app_dir', dirname(__file__));
include(zoop_dir . '/Zoop.php');
Zoop::loadLib('app');

echo "I am the parent\n";

// trigger_error('stuff');

$descriptorspec = array(
   0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
   1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
   2 => array("file", "/tmp/error-output.txt", "a") // stderr is a file to write to
);

$process = proc_open('./child.php', $descriptorspec, $pipes, getcwd(), array());

assert(is_resource($process));

while(true)
{
	static $i = 1;
	echo "writing to the stream $i\n";
	fwrite($pipes[0], "Message : parent $i\n");
	//fflush($pipes[0]);
	
	echo "getting the stream $i\n";
	$response = fgets($pipes[1], 4096);
	
	echo "printing the stream $i\n";
	echo "$response";
	
	// echo stream_get_contents($pipes[1]);
	echo "printed the stream $i\n\n";
	$i++;
}

fclose($pipes[0]);
fclose($pipes[1]);

// It is important that you close any pipes before calling
// proc_close in order to avoid a deadlock
$return_value = proc_close($process);

echo "command returned $return_value\n";
