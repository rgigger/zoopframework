<?php
include('config.php');
define('app_tmp_dir', app_dir . '/tmp');

include('includes.php');

ZoneApplication::handleRequest();