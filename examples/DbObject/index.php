<?php
include('config.php');
include(zoop_dir . '/Zoop.php');

include('domain/DbObjectExample.php');
DbObjectExample::init();

Zoop::loadLib('zone');
Zoop::loadLib('db');

Zoop::registerDomain('PersonStuff');
Zoop::registerDomain('GuidPerson');
Zoop::registerDomain('MultiPerson');

ZoneApplication::handleRequest();
