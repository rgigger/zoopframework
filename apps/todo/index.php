<?php
header("Content-type: text/html; charset=utf-8");

include('config.php');
include(dirname(__file__) . "/includes.php");

$zoneDefault = new zone_default();
$zoneDefault->handleRequest($PATH_ARRAY);