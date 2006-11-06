<?php
function RequestIsPost()
{
	return $_SERVER['REQUEST_METHOD'] == 'POST' ? 1 : 0;
}