<?php
class ZoneDefault extends AppZone
{
	public function initPages($p)
	{
		$this->loggedInUser = RequestApp::getLoggedInUser();
		if(!$this->loggedInUser && $p != 'default')
			$this->redirect('default');
	}
	
	public function pageDefault() {}
	
	public function postDefault()
	{
		if(RequestApp::auth($_POST['username'], $_POST['password']))
			$this->redirect('list');
	}
	
	function pageList()
	{
		$this->assign('requests', $this->loggedInUser->getPermittedRequests());
		$this->display('list');
	}
	
	function pageView($p)
	{
		$file = $p[1];
		$this->assign('filename', $file);
		$this->display('view');
	}
}
