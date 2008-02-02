<?php
include('config.php');
include(zoop_dir . '/Zoop.php');

Zoop::loadLib('zone');
Zoop::loadLib('db');
Zoop::loadLib('session');
require_once('misc/utils.php');

session::start();

ZoneApplication::handleRequest();