<?php
interface AuthStorageHandler
{
	abstract public function getInfo($username);
	abstract public function setInfo($username, $info);
}