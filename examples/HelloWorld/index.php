<?php
include('config.php');
include(pehppy_dir . '/Pehppy.php');

Pehppy::loadLib('zone');
ZoneApplication::handleRequest();