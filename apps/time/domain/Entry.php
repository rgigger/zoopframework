<?php
class Entry extends DbObject
{
	function stop()
	{
		SqlUpdateRow("update entry set endtime = now() where id = :id", array('id' => $this->id));
	}
}