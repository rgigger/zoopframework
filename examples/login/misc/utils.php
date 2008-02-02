<?php
function UserIsLoggedIn()
{
	return isset($_SESSION['personId']) && $_SESSION['personId'] ? 1 : 0;
}