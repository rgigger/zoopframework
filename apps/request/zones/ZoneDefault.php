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
	
	public function pageList()
	{
		/*
		$a = microtime(1);

		$mbox = imap_open ("{pop.gmail.com:995/pop3/ssl}", "rick@rickgigger.com", "giggles");

		echo "<h1>Headers in INBOX</h1>\n";
		$headers = imap_headers($mbox);

		$b = microtime(1);
		echo 'time = ' . ($b - $a) . '<br>';
		if ($headers == false) {
		    echo "Call failed<br />\n";
		} else {
		    foreach ($headers as $val) {
		        echo $val . "<br />\n";
		    }
		}

		imap_close($mbox);
		*/
		
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
		$request->setFields($_POST['_record']);
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
