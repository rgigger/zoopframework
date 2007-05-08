<?php
include('config.php');
include(zoop_dir . '/Zoop.php');
Zoop::loadLib('db');

$map = SqlFetchSimpleMap('select * from test', 'one', 'two', array());
echo_r($map);

$rows = SqlFetchRows('select * from test', array());
echo_r($rows);
