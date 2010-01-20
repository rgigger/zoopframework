<?php
class ZoneDefault extends AppZone
{
	public function initZone($p, $z)
	{
		$this->loggedInUser = RequestApp::getLoggedInUser();
		if(!$this->loggedInUser && $p[0] != 'default')
			BaseRedirect('default');
	}
	
	public function pageDefault() {}
	
	public function postDefault()
	{
		if(RequestApp::auth($_POST['username'], $_POST['password']))
			$this->redirect('list');
	}
	
	public function pageList()
	{
		// echo_r($this->loggedInUser->getPermittedRequests());
		$this->assign('requests', $this->loggedInUser->getPermittedRequests());
		$this->display('list');
	}
	
	public function pageEdit($p)
	{
		$request = isset($p[1]) && $p[1] ? new Request($p[1]) : new Request();
		$this->assign('request', $request);
	}
	
	public function postEdit($p)
	{
		if($_POST['submitAction'] == 'Save')
		{
			$request = isset($p[1]) && $p[1] ? new Request($p[1]) : new Request();
			$request->setFields(array_merge($_POST['_record'], array('owner_id' => $this->loggedInUser->id)));
			$request->save();
		}
		else if($_POST['submitAction'] == 'Delete')
		{
			assert(isset($p[1]) && $p[1]);
			$request = new Request($p[1]);
			$request->destroy();
		}
		
		$this->redirect('list');
	}
	
	public function pageView($p)
	{
		$file = $p[1];
		$this->assign('filename', $file);
		$this->display('view');
	}
	
	public function postSetField($p, $z)
	{
		$id = $_POST['id'];
		$field = $_POST['field'];
		$request = new Request($id);
		
		if($field == 'completed')
			$request->completed = $_POST['update_value'];
		else if($field == 'priority')
			$request->priority_id = SqlFetchCell("select id from priority where name = :name", array('name' => $_POST['update_value']));
		else
			trigger_error("undefined field: $field");
		
		$request->save();
		
		//	this is sent back and thus placed in the table cell
		echo $_POST['update_value'];
	}
}
