<?php
class MultiPerson extends DbObject
{
	protected function init()
	{
		$this->keyAssignedBy = self::keyAssignedBy_dev;
		$this->primaryKey = array('one', 'two', 'three');
	}
}