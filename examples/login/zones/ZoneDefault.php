<?php
class ZoneDefault extends GuiZone
{
	public function initPages()
	{
	}
	
	public function closePages($p)
	{
		$this->assign('loggedIn', UserIsLoggedIn());
		$this->display('header');
		$this->display($p[0]);
	}
	
	function pageDefault()
	{
		$this->redirect('login');
	}
	
	function pageLogin($p)
	{
		$bad = isset($p[1]) && $p[1] == 'bad' ? 1 : 0;
		$this->assign('bad', $bad);
		
	}
	
	public function postLogin()
	{
		if($_POST['username'] == 'test' && $_POST['password'] == 'test')
		{
			$_SESSION['personId'] = 1;
			session::saveChangesUnsafe();
			$this->redirect('protected');
		}
		
		$this->redirect('login/bad');
	}
	
	function pageProtected()
	{
		if(!UserIsLoggedIn())
			$this->redirect('login');
		$this->assign('session', $_SESSION);
	}
	
	public function pageLogout()
	{
		session::destroy();
		$this->redirect('login');
	}
}
