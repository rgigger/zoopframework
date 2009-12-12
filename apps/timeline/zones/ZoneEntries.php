<?
class ZoneEntries extends AppZone
{
	public function pageDefault($p, $z)
	{
		$entries = DbObject::_find('Entry');
		$this->assign('entries', $entries);
		// echo_r($entries);
	}
	
	public function pageAdd($p, $z)
	{
	}
	
	public function postAdd($p, $z)
	{
		//	update Zoop::Form so that it it can handle consolidating the edit and view pages here
		$entry = new Entry();
		$entry->start = $_POST['start'];
		$entry->end = $_POST['end'];
		$entry->title = $_POST['title'];
		$entry->is_duration = isset($_POST['is_duration']) && $_POST['is_duration'] ? 1 : 0;
		$entry->save();
		$this->redirect('');
	}
	
	public function pageEdit($p, $z)
	{
		$entry = new Entry(isset($p[1]) ? $p[1] : null);
		// echo $entry->start;
		// echo_r($entry);
		$this->assign('entry', $entry);
	}
	
	public function postEdit($p, $z)
	{
		Form::save();
		$this->redirect('');
	}
}
