<?php
include('config.php');
include(zoop_dir . '/Zoop.php');

Zoop::loadLib('zone');

include(dirname(__file__) . "/includes.php");

ZoneApplication::handleRequest();