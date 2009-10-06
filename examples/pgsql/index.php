<?php
include('config.php');
include(zoop_dir . '/Zoop.php');
Zoop::loadLib('db');

$map = SqlFetchSimpleMap('select * from test', 'name', 'value', array());
echo_r($map);

$rows = SqlFetchRows('select * from test', array());
echo_r($rows);

$rows = SqlFetchRows('select * from test where id = :id', array('id' => 2));
echo_r($rows);

$rows = SqlFetchRows('select id::int from test', array());
echo_r($rows);
