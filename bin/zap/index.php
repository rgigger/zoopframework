<?php
include(dirname(__file__) . '/config.php');
include(zoop_dir . '/Zoop.php');
include_once(dirname(__file__) . "/ZoneCreate.php");
include_once(dirname(__file__) . "/ZoneTest.php");
include_once(dirname(__file__) . "/ZoneApply.php");
include_once(dirname(__file__) . "/ZoneRedo.php");

Config::setConfigFile(getcwd() . '/' . 'config.yaml');
Config::load();
Zoop::loadLib('app');
Zoop::loadLib('cli');
Zoop::loadLib('gui');
Zoop::loadLib('db');
Zoop::loadLib('migration');
CliApplication::handleRequest();

