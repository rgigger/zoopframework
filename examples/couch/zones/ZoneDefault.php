<?
class ZoneDefault extends AppZone
{
	function pageDefault()
	{
		$this->redirect('list');
	}
	
	public function pageList()
	{
		$all = CouchDocument::findAll();
		foreach($all as $thisOne)
			$thisOne->load();
		$this->assign('all', $all);
	}
	
	public function pageAdd($p, $z)
	{
	}
	
	public function postAdd($p, $z)
	{
		$doc = new CouchDocument($_POST['id']);
		foreach($_POST as $key => $value)
		{
			$doc->$key = $value;
		}
		$doc->save();
		
		$this->redirect('list');
	}
}