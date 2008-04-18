<?php
class ZoneClient extends AppZone
{
	function __construct()
	{
		$this->mixin('DbZone', array('class' => 'Client'));
	}
}
