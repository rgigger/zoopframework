<?php
include('config.php');
include('includes.php');
session::start();
ZoneApplication::handleRequest();
