<?php
include(dirname(__file__) . '/config.php');
include(zoop_dir . '/Zoop.php');
include_once(dirname(__file__) . "/ZoneCreate.php");

Zoop::loadLib('cli');
Zoop::loadLib('gui');
CliApplication::handleRequest();

