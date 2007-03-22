<?php
define('zoop_dir', '../../framework');
define('app_dir', dirname(__file__));

//	db config
define('db_driver', 'pgsql_php');
define('db_database', 'test');
define('db_username', 'postgres');

include(zoop_dir . '/Zoop.php');
Zoop::loadLib('db');

$map = SqlFetchSimpleMap('select * from test', 'key', 'value', array());
echo_r($map);