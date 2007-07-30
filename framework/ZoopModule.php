I didn't end up using this becuase it seemed like all I needed was a simple include for each module.  I'm leaving it in here though in case that situation changes at some point
<?php
class ZoopModule
{
	function load()
	{
		trigger_error("virtual function ZoopModule::load()");
	}
}
