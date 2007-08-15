<?php
include('config.php');
include(zoop_dir . '/Zoop.php');

Zoop::loadLib('zone');
Zoop::loadLib('db');

Zoop::registerClass('AppZone', app_dir . '/extend/AppZone.php');
Zoop::registerClass('ZonePerson', app_dir . '/zones/ZonePerson.php');
Zoop::registerClass('Person', app_dir . '/domain/Person.php');

ZoneApplication::handleRequest();