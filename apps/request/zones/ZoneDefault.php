<?php
class ZoneDefault extends AppZone
{
	public function initPages($p)
	{
		$this->loggedInUser = RequestApp::getLoggedInUser();
		if(!$this->loggedInUser && $p[0] != 'default')
			$this->redirect('default');
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
		$request = isset($p[1]) && $p[1] ? new Request($p[1]) : new Request();
		$request->setFields(array_merge($_POST['_record'], array('owner_id' => $this->loggedInUser->id)));
		$request->save();
		$this->redirect('list');
	}
	
	function pageView($p)
	{
		$file = $p[1];
		$this->assign('filename', $file);
		$this->display('view');
	}
}
