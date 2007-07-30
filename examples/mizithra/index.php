<?php
include('config.php');
include(zoop_dir . '/Zoop.php');

Zoop::loadLib('mizithra');

$gui = new Mizithra();

$gui->display('test.miz');