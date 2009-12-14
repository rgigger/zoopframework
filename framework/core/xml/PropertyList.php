<?
class PropertyList
{
	public $data;
	
	function PropertyList($filename)
	{
		$doc = new XmlDom();
		$rootNode = $doc->parseFile($filename);
		$this->handlePList($rootNode);
	}
	
	function handlePList($curNode)
	{
		$children = $curNode->getChildren();
		for($thisNode = $children->current(); $children->valid(); $thisNode = $children->next())
		{
			if($thisNode->getName() != '#text')
			{
				$this->data = array();
				$this->handleDict($thisNode, $this->data);
			}
		}
	}
	
	function handleDict($curNode, &$dataNode)
	{
		$curKey = NULL;
		$children = $curNode->getChildren();
		for($thisNode = $children->current(); $children->valid(); $thisNode = $children->next())
		{
			{
				switch($thisNode->getName())
				{
					case 'key':
						$nodeContent = $thisNode->getTextContent();
						$curKey = $nodeContent['content'];
						break;
					//	these next 4 could probably just be one rule
					case 'string':
						assert($curKey !== NULL);
						$nodeContent = $thisNode->getTextContent();
						$dataNode[$curKey] = $nodeContent['content'];
						$curKey = NULL;
						break;
					case 'integer':
						assert($curKey !== NULL);
						$nodeContent = $thisNode->getTextContent();
						$dataNode[$curKey] = $nodeContent['content'];
						$curKey = NULL;
						break;
					case 'real':
						assert($curKey !== NULL);
						$nodeContent = $thisNode->getTextContent();
						$dataNode[$curKey] = $nodeContent['content'];
						$curKey = NULL;
						break;
					case 'true':
						assert($curKey !== NULL);
						$dataNode[$curKey] = 1;
						$curKey = NULL;
						break;
					case 'false':
						assert($curKey !== NULL);
						$dataNode[$curKey] = 0;
						$curKey = NULL;
						break;
					case 'dict':
						assert($curKey !== NULL);
						$dataNode[$curKey] = array();
						$this->handleDict($thisNode, $dataNode[$curKey]);
						$curKey = NULL;
						break;
					case 'array':
						assert($curKey !== NULL);
						$dataNode[$curKey] = array();
						$this->handleArray($thisNode, $dataNode[$curKey]);
						$curKey = NULL;
						break;
				}
			}
		}
	}
	
	function handleArray($curNode, &$dataNode)
	{
		$children = $curNode->getChildren();
		for($thisNode = $children->current(); $children->valid(); $thisNode = $children->next())
		{
			{
				switch($thisNode->getName())
				{
					case 'dict':
						$newDict = array();
						$this->handleDict($thisNode, $newDict);
						$dataNode[] = $newDict;
						break;
					case 'string':
						$dataNode[] = $thisNode->getTextContent();
						break;
				}
			}
		}
	}
}
?>