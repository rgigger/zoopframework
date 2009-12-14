<?
class XmlDom
{
	var $xmlTree;
	
	//	returns the root node
	function parseText($xml)
	{
		$this->xmlTree = new DOMDocument();
		$this->xmlTree->loadXML($xml);
		return new XmlNode($this->xmlTree->firstChild);
	}
	
	function parseFile($fileName)
	{
		$this->xmlTree = new DOMDocument();
//		print_r($this->xmlTree);
		$this->xmlTree->load($fileName);
//		print_r($this->xmlTree->documentElement->firstChild->nextSibling->nodeName);
//		die('here');
		return new XmlNode($this->xmlTree->documentElement);	
	}
}
?>
