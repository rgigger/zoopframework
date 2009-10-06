<?php
class XmlModule extends ZoopModule
{
	function getIncludes()
	{
		return array('utils.php');
	}
	
	function getClasses()
	{
		return array('XmlDom', 'XmlNode', 'XmlNodeList', 'PropertyList', 'XmlParser', 
						'XmlContainer', 'ProgressivePropertyList');
	}
	
	function configure()
	{
		
	}
}
