<?php
include(zoop_dir . '/Zoop.php');
Zoop::loadLib('zone');
Zoop::loadLib('db');
Zoop::loadLib('session');

session::start();

//	register classess in the application that extend Zoop classes
Zoop::registerClass('AppZone', dirname(__file__) . '/extend/AppZone.php');
Zoop::registerClass('AppGui', dirname(__file__) . '/extend/AppGui.php');

//	register domain classess in the application
Zoop::registerClass('Client', dirname(__file__) . '/domain/Client.php');
Zoop::registerClass('Invoice', dirname(__file__) . '/domain/Invoice.php');

//	list the zones that need to be included
ZoneApplication::loadZone('client');
ZoneApplication::loadZone('invoice');
