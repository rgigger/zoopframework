<?php
include(dirname(__file__) . '/config.php');
include(zoop_dir . '/Zoop.php');
include_once(dirname(__file__) . "/ZoneCreate.php");
include_once(dirname(__file__) . "/ZoneTest.php");

Zoop::loadLib('cli');
Zoop::loadLib('gui');
CliApplication::handleRequest();

