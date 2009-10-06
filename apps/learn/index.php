<?php
include('config.php');
include(zoop_dir . '/Zoop.php');

Zoop::loadLib('zone');
Zoop::loadLib('db');

Zoop::registerClass('Learn', app_dir . '/domain/Learn.php');
Zoop::registerClass('Board', app_dir . '/domain/Board.php');
Zoop::registerClass('Cell', app_dir . '/domain/Cell.php');

ZoneApplication::handleRequest();
