<?php
die('this example does not work.  Mail support is currently experimental');
include('config.php');
include(zoop_dir . '/Zoop.php');

Zoop::loadLib('gui');
Zoop::loadLib('mail');

$message = new Message();
$message->assign('asdf', 'some text to include in the body of the email');
$message->sendText('messages/test.tpl', 'example@localhost', 'Billy Joe', 'jim@dundermifflin.com', 'Jim Halpert', 'the subject of the email');