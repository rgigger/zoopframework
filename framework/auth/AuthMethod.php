<?php
abstract class AuthMethod
{
	abstract public function checkPassword($password, $userInfo);
}