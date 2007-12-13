<?php
class GraphicModule extends ZoopModule
{
	function getClasses()
	{
		return array('GraphicParser', 'GraphicDocument', 'GraphicContainer', 'GraphicStyleStack', 'GraphicStyle',
						'GraphicDiv', 'ZendPdfContext', 'PdfContext', 'GraphicContext', 'GraphicObject', 'GraphicTextRun',
						'GraphicHardBrokenLine', 'GraphicSoftBrokenLine');
	}
	
	function getDepends()
	{
		return array('Xml');
	}
	
	function configure()
	{		
	}
}
