<?php
include('config.php');
include(zoop_dir . '/Zoop.php');

Zoop::loadLib('zone');

ZoneApplication::handleRequest();
