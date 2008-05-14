<?php
include(zoop_dir . '/Zoop.php');
Zoop::loadLib('zone');
Zoop::loadLib('db');
Zoop::loadLib('session');

//	register classess in the application that extend Zoop classes
Zoop::registerClass('AppZone', dirname(__file__) . '/extend/AppZone.php');
Zoop::registerClass('AppGui', dirname(__file__) . '/extend/AppGui.php');

//	register domain classess in the application
Zoop::registerClass('RequestApp', dirname(__file__) . '/domain/RequestApp.php');
Zoop::registerClass('Person', dirname(__file__) . '/domain/Person.php');
Zoop::registerClass('Request', dirname(__file__) . '/domain/Request.php');
