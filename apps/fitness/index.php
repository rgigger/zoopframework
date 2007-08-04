<?php
include('config.php');
include(zoop_dir . '/Zoop.php');

Config::load();
Zoop::loadLib('zone');
Zoop::loadLib('db');


ZoneApplication::handleRequest();