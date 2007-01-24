<?php
//
//	pehppy stuff
//

include(pehppy_dir . '/Pehppy.php');
include_once(pehppy_dir . "/app/app_framework.php");
include_once(pehppy_dir . "/db/db_framework.php");
include_once(pehppy_dir . "/gui2/gui_framework.php");

//
//	app stuff
//

//	extend
pehppy::registerClass('AppZone', dirname(__file__) . '/extend/AppZone.php');
pehppy::registerClass('AppGui', dirname(__file__) . '/extend/AppGui.php');

//	zones
pehppy::registerClass('zone_default', dirname(__file__) . '/zones/zone_default.php');

//	domain
pehppy::registerClass('Page', dirname(__file__) . '/domain/Page.php');
pehppy::registerClass('PageItem', dirname(__file__) . '/domain/PageItem.php');