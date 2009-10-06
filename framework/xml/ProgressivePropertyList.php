<?
class ProgressivePropertyList
{
	public $data;
	private $doc;
	
	function __construct($filename)
	{
		$this->doc = new XMLReader();
		$this->doc->open($filename);
		$this->handlePList();
	}
	
	function handlePList()
	{
		$count = 0;
		while($this->doc->read())
		{
			if($this->doc->nodeType == XMLREADER::ELEMENT)
			{
				echo $this->doc->localName . '<br>';
				echo 'value=' . $this->doc->value . '<br>';
				echo '<br>';
			}
			if($count++ > 400)
				die('done');
		}
		
		// while ($reader->read())
		// {
		// 	
		// 	switch ($reader->nodeType)
		// 	{
		// 		case (XMLREADER::ELEMENT):
		// 			if ($reader->localName == "entry")
		// 			{
		// 				if ($reader->getAttribute("ID") == 5225)
		// 				{
		// 					while ($reader->read())
		// 					{
		// 						if ($reader->nodeType == XMLREADER::ELEMENT)
		// 						{
		// 							if ($reader->localName == "title")
		// 							{
		// 								$reader->read();
		// 								echo $reader->value;
		// 								break;
		// 							}
		// 							if ($reader->localName == "entry")
		// 							{
		// 								break;
		// 							}
		// 						}
		// 					}
		// 				}
		// 			}
		// 	}
		// }
		
		
		/*
		$children = $curNode->getChildren();
		for($thisNode = $children->current(); $children->valid(); $thisNode = $children->next())
		{
			if($thisNode->getName() != '#text')
			{
				$this->data = array();
				$this->handleDict($thisNode, $this->data);
			}
		}
		*/
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