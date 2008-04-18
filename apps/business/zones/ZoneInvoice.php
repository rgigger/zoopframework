<?php
class ZoneInvoice extends AppZone
{
	function __construct()
	{
		$this->mixin('DbZone', array('class' => 'Invoice'));
	}
}
