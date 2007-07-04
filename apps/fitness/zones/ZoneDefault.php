<?
class ZoneDefault extends zone
{
	function pageDefault()
	{
		$sql = "select
					*
				from
					session s
					inner join session_exercise se on s.id = se.session_id
					inner join person p on se.person_id = p.id";
		$data = SqlFetchRows($sql, array());
		echo_r($data);
	}
}