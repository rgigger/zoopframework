<?php
class DbObjectExample
{
	static public function init()
	{
		//	make sure the db exists
		$dbdir = Zoop::getTmpDir() .'/db';
		if(!file_exists($dbdir))
			mkdir($dbdir, 0777, true);
		$dbfile = "$dbdir/data.db";
		if(!file_exists($dbfile))
			copy(app_dir . '/var/data.db', $dbfile);
		
		//	make sure tmp/gui exists
		$compiledir = Zoop::getTmpDir() .'/gui';
		if(!file_exists($compiledir))
			mkdir($compiledir, 0777, true);
	}
}