<?
class ZoneDefault extends GuiZone
{
	function pageDefault()
	{
		$_SESSION['personId'] += 1;
		$this->display('default');
		session::saveChangesUnsafe();
		echo_r($_SESSION);
	}
	
	function pageNext()
	{
		$_SESSION['nextId'] = $_SESSION['nextId'] + 1;
		session::saveChangesUnsafe();
		echo_r($_SESSION);
	}
	
	function pageAfter()
	{
		$_SESSION['afterId'] += 1;
		session::saveChangesUnsafe();
		echo_r($_SESSION);
	}
}