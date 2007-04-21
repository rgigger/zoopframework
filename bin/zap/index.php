<?php
include('config.php');
include(zoop_dir . '/Zoop.php');
include(app_dir . '/ZoneCreate.php');

Zoop::loadLib('cli');
CliApplication::handleRequest();