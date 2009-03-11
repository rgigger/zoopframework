#!/usr/bin/env php
<?php
define('zoop_dir', dirname(__file__) . '/../../framework');
define('app_dir', dirname(__file__));
include(zoop_dir . '/Zoop.php');
Zoop::loadLib('Pherver');

$chatserver = new HttpServer();
$chatserver->run('127.0.0.1', 9050);
