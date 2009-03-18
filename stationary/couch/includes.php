<?php
include(zoop_dir . '/Zoop.php');
Zoop::loadLib('zone');
Zoop::loadLib('couch');

//	register classess in the application that extend Zoop classes
Zoop::registerClass('AppZone', dirname(__file__) . '/extend/AppZone.php');
Zoop::registerClass('AppGui', dirname(__file__) . '/extend/AppGui.php');