<?php
include('config.php');
include(zoop_dir . '/Zoop.php');

Zoop::loadLib('zend');
Zoop::loadLib('graphic');

$context = new ZendPdfContext();
$context->addText(100, 100, "Hello ZendGraphicContext");
$context->display();
