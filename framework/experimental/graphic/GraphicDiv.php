<?php
class GraphicDiv extends GraphicContainer
{
	private $lines;
	private $startLine;
	private $maxWidth;
	
	public function __construct($init)
	{
		parent::__construct($init);
		$this->startLine = 0;
	}
	
	//
	//	accessor functions
	//
	public function setMaxWidth($width)
	{
		$this->maxWidth = $width;
	}
	
	//	these functions define what children this container is allowed to have
	//	eventually you could probably have a config file defining this and most of
	//	these functions could be replaced with something more generic (for instance
	//	a __call function with a filter for 'getNew' and an array of legit object types)
	
	public function getNewTextRun()
	{
		return $this->getNewChild('GraphicTextRun');
	}
	
	public function getNewDiv()
	{
		return $this->getNewChild('GraphicDiv');
	}
	
	
	//
	//	functions for drawing the div
	//
	
	function breakIntoHardBrokenLines()
	{
		if($this->lines !== NULL)
			return;
		
		$this->lines = array();
		$curLine = 0;
		$firstItem = 1;
		$previousItemHadRightSpace = 0;
		$previousItemWasInline = 0;
		
		//	if the div isn't totally empty then initialize the first line
		if(count($this->children) > 0)
		{
			$this->lines[$curLine] = new GraphicHardBrokenLine();
			// $this->lines[$curLine]->setLineSpacing($this->lineSpacing);
			// if($this->bottomIndent)
			// 	$this->lines[$curLine]->setBottomIndent($this->bottomIndent);
		}

		//	loop through all of the children.  If they are dynamically positioned then
		//	form them into hard broken lines. 
		foreach(array_keys($this->children) as $childKey)
		{
			//	get a reference to this child
			$thisChild = $this->children[$childKey];
			// echo "<br><strong>$childKey - drawing child:</strong><br>";			
			// $thisChild->getObjectTree();
			/*
			//	if it's absolute positioned then we don't want to add it to a line
			if($thisChild->getPosition() == 'container')
				continue;
			*/
			
			//	if this item is not inline or the previous item was not inline 
			//		and this is not the very first item then we need to go to the next line
			if((!$thisChild->isInline() || !$previousItemWasInline) && !$firstItem)
			{
				$curLine++;
				$this->lines[$curLine] = new GraphicHardBrokenLine();
				// $this->lines[$curLine]->setLineSpacing($this->lineSpacing);
				// if($this->bottomIndent)
				// 	$this->lines[$curLine]->setBottomIndent($this->bottomIndent);
			}
			
			$this->lines[$curLine]->addMember($thisChild);
			$firstItem = 0;
			
			$previousItemWasInline = $thisChild->isInline();
			
			// echo "<br><strong>drawing first line:</strong><br>";			
			// $this->lines[0]->drawRenderTree();
			// echo "<strong>done drawing first lines</strong><br><br>";			
			/*
			echo "<br>drawing lines:<br>";
			foreach(array_keys($this->lines) as $lineKey)
			{
				$this->lines[$lineKey]->drawRenderTree();
			}
			*/
		}
	}
	
	function doneDrawing()
	{
		if($this->lines === NULL)
			return false;
		
		return $this->startLine < count($this->lines) ? 0 : 1;
	}
	
	function drawContent($x, $y, $width, $reallyDraw = true)
	{
		$cury = $y;
		
		//	loop through each hard broken line and draw it
		for($lineKey = $this->startLine; $lineKey < count($this->lines); $lineKey++)
		{
//			echo "line number $lineKey<br>";
			
			
			//	get a reference to the current line
			$thisLine = $this->lines[$lineKey];
			
			//	we want to take the smaller of the passed in width and the content width
			// $cw = $this->getContentWidth();
			// $contentWidth = $width < $cw ? $width : $cw;
//			echo "width = $width<br>";
			$contentWidth = $width;
			
			//	check to see if drawing this line is going to put us past the end of the page
			//	if $this is not breakable then we already know that the whole thing should fit
			//	so we don't need to do this check
			// if($this->isBreakable() && $reallyDraw)
			// {
			// 	$minHeight = $thisLine->getMinHeight($contentWidth);
			// 	
			// 	$document = $this->getAncestor('GraphicDocument');
			// 	
			// 	if($document)
			// 	{
			// 		if( $cury + $minHeight > $document->getPageBottom() )
			// 			break;
			// 	}
			// }
			
			//	the line inherits the alignment of the div
			// $thisLine->setAlignment($this->getAlignment());
			
			//	actually draw the line
//			echo "drawing hard broken line $reallyDraw<br>";
			$cury += $thisLine->draw($x, $cury, $contentWidth, $reallyDraw);
			
			//	if we drew part but not all then we need to leave the loop now to make sure we don't move past it
			// if($this->isBreakable() && $reallyDraw && !$thisLine->doneDrawing())
			// 	break;
			
			// if($thisLine->forcePageBreak())
			// {
			// 	$lineKey++;
			// 	break;
			// }
		}
		
		//	update the pointer to the current line
		// if($reallyDraw && !$this->repeatable)
		// 	$this->startLine = $lineKey;
		
//		echo_backtrace();
//		echo get_class($this) . ' start line = ' . $this->startLine . '<Br>';
		
		return $cury - $y;
	}

	public function draw($x, $y, $width, $reallyDraw = true)
	{
//		echo "drawing div: $x, $y, $width, $reallyDraw<br>";
		
		//	fix the width
		// $width = $width ? $width : 0;
		$width = $width !== NULL && $width < $this->maxWidth ? $width : $this->maxWidth;
		
		//	break into lines if it hasn't already been done
		$this->breakIntoHardBrokenLines();
		
		$cury = $y;
		
		//	draw the background
		//	draw the border
		//	draw the content
		$cury += $this->drawContent($x, $y, $width, $reallyDraw);
		
		return $cury - $y;
	}
	
	public function drawRenderTree($indentLevel = 0)
	{
		//	break into lines if it hasn't already been done
		$this->breakIntoHardBrokenLines();
		
		$tabs = '';
		for($i = 0; $i < $indentLevel; $i++)
			$tabs .= '&nbsp;&nbsp;&nbsp;&nbsp;';
		
		//	draw the open tag
		echo $tabs . '&lt;' . get_class($this) . '&gt;<br>';
		
		//*
		//	loop through each hard broken line and draw the render tree
		for($lineKey = $this->startLine; $lineKey < count($this->lines); $lineKey++)
		{
			//	get a reference to the current line
			$this->lines[$lineKey]->drawRenderTree($indentLevel + 1);

		}
		//*/
		
		//	draw the close tag
		echo $tabs . '&lt;/' . get_class($this) . '&gt;<br>';
	}
	
	public function __toString()
	{
		return '&lt;' . get_class($this) . '/&gt;<br>';
	}
}
