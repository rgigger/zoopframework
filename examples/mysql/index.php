<?php
include('config.php');
include(zoop_dir . '/Zoop.php');
Zoop::loadLib('db');

$map = SqlFetchSimpleMap('select * from test', 'name', 'value', array());
echo_r($map);

$rows = SqlFetchRows('select * from test', array());
echo_r($rows);

$rows = SqlFetchRows('select * from test where name = :name', array('name' => 'one'));
echo_r($rows);
