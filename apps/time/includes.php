<?php
include(zoop_dir . '/Zoop.php');

//	load zoop modules
Zoop::loadLib('zone');
Zoop::loadLib('db');
Zoop::loadLib('session');

//	start the session
session::start();

//	register "extend" classes
Zoop::registerClass('AppZone', dirname(__file__) . '/extend/AppZone.php');
Zoop::registerClass('AppGui', dirname(__file__) . '/extend/AppGui.php');
Zoop::registerClass('AppMainGui', dirname(__file__) . '/extend/AppMainGui.php');

//	register "domain" classes
Zoop::registerClass('Time', dirname(__file__) . '/domain/Time.php');
Zoop::registerClass('Person', dirname(__file__) . '/domain/Person.php');
Zoop::registerClass('Entry', dirname(__file__) . '/domain/Entry.php');
