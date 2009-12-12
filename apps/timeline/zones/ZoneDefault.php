<?
class ZoneDefault extends AppZone
{
	public function pageDefault($p, $z)
	{
		
	}
	
	public function pageTimeline()
	{
		$this->layout = 'plain';
	}
	
	public function pageData($p, $z)
	{
		header('Content-type: text/xml');
		$this->layout = 'plain';
		
		$entries = DbObject::_find('Entry');
		$this->assign('entries', $entries);		
	}
}