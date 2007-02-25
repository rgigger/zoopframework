<?php
include(zoop_dir . '/Zoop.php');
Zoop::loadLib('zone');
//Zoop::loadLib('db');

include_once(dirname(__file__) . "/domain/TodoListCollection.php");
include_once(dirname(__file__) . "/domain/TodoList.php");
include_once(dirname(__file__) . "/domain/TodoListParser.php");
include_once(dirname(__file__) . "/domain/TodoListItem.php");