<?php
include(zoop_dir . '/Zoop.php');

Zoop::loadMod('session');
Zoop::loadMod('zone');

//	start the session
session::start();

//	register classess in the application that extend Zoop classes
Zoop::registerClass('AppZone', dirname(__file__) . '/extend/AppZone.php');
Zoop::registerClass('AppGui', dirname(__file__) . '/extend/AppGui.php');

//	register the zones
Zoop::registerClass('ZoneEntries', dirname(__file__) . '/zones/ZoneEntries.php');

//	register domain classes
Zoop::registerClass('Entry', dirname(__file__) . '/domain/Entry.php');
