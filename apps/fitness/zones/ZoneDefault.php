<?php
class ZoneDefault extends Zone
{
	function pageDefault()
	{
		/*
		$sql = "select
					*
				from
					session s
					inner join session_exercise se on s.id = se.session_id
					inner join person p on s.person_id = p.id";
		$data = SqlFetchRows($sql, array());
		echo_r($data);
		*/
		
		$this->switchZone('person');
	}
}