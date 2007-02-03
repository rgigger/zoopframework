<?php
include('config.php');
include('includes.php');

$db = new DbPgsql();
$db->query(':a is the :coolest', array('a:int' => '42a6', 'coolest' => 'l33t'));

ZoneApplication::handleRequest();