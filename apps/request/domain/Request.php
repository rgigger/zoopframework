<?php
class Request extends DbObject
{
	protected function init()
	{
		$this->belongsTo('Person', array('local_field' => 'owner_id'));
	}
}
