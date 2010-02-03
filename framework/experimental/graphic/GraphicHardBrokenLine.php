<?php
class GraphicHardBrokenLine extends GraphicObject
{
	var $members;
	var $alignment;
	var $lineSpacing;
	var $doneDrawing;
	var $topIndent;
	var $bottomIndent;
	
	function GraphicHardBrokenLine()
	{
		$this->members = array();
		$this->alignment = 'left';
		$this->doneDrawing = 0;
	}
	
	function getUnwrappedWidth()
	{
		$total = 0;
		for($curMember = 0; $curMember < count($this->members); $curMember++)
		{
//			echo get_class($this->members[$curMember]);
			$total += $this->members[$curMember]->getUnwrappedWidth();
		}
		return $total;
	}
	
	function setBottomIndent($indent)
	{
		$this->bottomIndent = $indent;
	}
	
	function setLineSpacing($lineSpacing)
	{
		$this->lineSpacing = $lineSpacing;
	}
	
	function addMember($member)
	{
		$this->members[] = $member;
	}
	
	function setAlignment($alignment)
	{
		$this->alignment = $alignment;
	}
	
	function isBreakable()
	{
		return $this->members[0]->isBreakable();
	}
	
	function getMinHeight($width)
	{
		if($this->isBreakable())
		{
			return $this->members[0]->getMinHeight($width);
		}
		else
		{
			return $this->getHeight($width);
		}
	}
	
	function doneDrawing()
	{
		return $this->doneDrawing;
	}
	
	function forcePageBreak()
	{
		return $this->members[0]->forcePageBreak();
	}
	
	function draw($x, $y, $width, $reallyDraw = 1)
	{
		// echo "drawing hard broken line: $x, $y, $width, $reallyDraw<br>";
		
		// $width += 20;
		
		//	if there is nothing in it then just return 0
		if(count($this->members) == 0)
			return 0;
		
		/*
		//	if this is not an inline item then it should be all by itself and we
		//	need to just draw it and return		
		if( !$this->members[0]->isInline() )
		{
			assert( count($this->members[0]) == 1);
			
			switch($this->alignment)
			{
				case 'left':
					$curx = $x;
					break;
				case 'center':
					$curx = $x + (($width - $this->members[0]->getWidth()) / 2);
					break;
				case 'right':
					$curx = $x + $width - $this->members[0]->getWidth();
					break;
				default:
					trigger_error('invalid alignment specified: ' . $this->alignment);
					break;
			}
			
			$height = $this->members[0]->draw($curx, $y, $width, $reallyDraw);
			
			$this->doneDrawing = $this->members[0]->doneDrawing();
			
			return $height;
		}
		*/
		assert($this->members[0]->isInline());
		
		//
		//	this is a series of one or more inline items
		//	we need to draw them all and wrap them to the width
		//
		
		//
		//	break everything up into soft broken lines
		//
		
		$curMemberNumber = 0;
		$lines = array();
		$curLine = 0;
		//$lines[$curLine] = &new GraphicSoftBrokenLine();
		$curPos = 0;
		$remainingWidth = $width;
		
		while($curMemberNumber < count($this->members))
		{
			//	get and verify the current member
			$curMember = $this->members[$curMemberNumber];
			assert(is_a($curMember, 'GraphicTextRun'));
			
			//	figure out how much if any of this member will fit onto the current line
			$fitsLength = $curMember->getFitsLength($curPos, $remainingWidth);
			// echo "$curPos, $remainingWidth $fitsLength<br>";
			assert($curMember->getLength() >= $curPos + $fitsLength);
			
			//	if it all fits add it to the soft broken line, bump the member number and continue from the top
			if($curPos + $fitsLength == $curMember->getLength())
			{
				if(!isset($lines[$curLine]))
					$lines[$curLine] = &new GraphicSoftBrokenLine();
				
				$lines[$curLine]->addEntry($curMember, $curPos, $fitsLength);
				$remainingWidth -= $curMember->getPartWidth($curPos, $fitsLength);
				$curMemberNumber++;
				$curPos = 0;
			}
			
			//	if only part fits, add that part, bump the line number and continue from the top
			else if($fitsLength > 0)
			{
				if(!isset($lines[$curLine]))
					$lines[$curLine] = &new GraphicSoftBrokenLine();
				
				$lines[$curLine]->addEntry($curMember, $curPos, $fitsLength);
				$curPos += $fitsLength;
				
				$curLine++;
				$remainingWidth = $width;
				// if($this->bottomIndent)
				// 	$remainingWidth -= $this->bottomIndent;
			}
			
			//	if none fits bump the line number and continue from the top
			else
			{
				if($remainingWidth == $width)
					trigger_error("single word will not fit in space");
				
				$curLine++;
				$remainingWidth = $width;
			}
		}
		
		/*
		while(true)
		{
			//	if everything is already handled then just break out of the loop
			if($curMember >= count($this->members))
				break;
			
			//	figure out how much of this member will fit onto the current line
			$fitsLength = $this->members[$curMember]->getFitsLength($curPos, $remainingWidth);
			
			//	if the whole thing fits on the line
			if($curPos + $fitsLength >= $this->members[$curMember]->getLength())
			{
				//	if this is the first item on the line then first create the line
				if(!isset($lines[$curLine]))
					$lines[$curLine] = &new GraphicSoftBrokenLine();
				
				$lines[$curLine]->addEntry($this->members[$curMember], $curPos, $fitsLength);
				
				$remainingWidth -= $this->members[$curMember]->getPartWidth($curPos, $fitsLength);
				$curMember++;
				$curPos = 0;
			}
			//	if it doesn't all fit but some fits
			else if($fitsLength > 0)
			{
				//	if this is the first item on the line then first create the line
				if(!isset($lines[$curLine]))
					$lines[$curLine] = &new GraphicSoftBrokenLine();
				
				$lines[$curLine]->addEntry($this->members[$curMember], $curPos, $fitsLength);
				$curPos += $fitsLength;
				
				$curLine++;
				$remainingWidth = $width;
				if($this->bottomIndent)
					$remainingWidth -= $this->bottomIndent;
			}
			//	if none of it will fit???
			else
			{
				echo "$curPos $fitsLength " . $this->members[$curMember]->getLength() . "<br>";
				BUG("This should never happen.  Most likely you are trying to squish some text into to small of a space");
				die();
				$curLine++;
				$remainingWidth = $width;
			}
			
		}
		
		//
		//	now draw each of the lines
		//
		
		$cury = $y;
		//if($reallyDraw)
		//	echo 'soft lines = ' . count($lines) . '<br>';		
		foreach($lines as $lineKey => $dummyLine)
		{
			$thisLine = &$lines[$lineKey];
			
			switch($this->alignment)
			{
				case 'left':
					$curx = $x;
					if($this->bottomIndent && $lineKey > 0)
						$curx += $this->bottomIndent;
					break;
				case 'center':
					$curx = $x + ( ($width - $thisLine->getWidth()) / 2 );
					break;
				case 'right':
					$curx = $x + ( $width - $thisLine->getWidth() );
					break;
				default:
					trigger_error('invalid alignment specified: ' . $this->alignment);
					break;
			}
			
			
			$thisLine->draw($curx, $cury, 0, $reallyDraw);
			
			$cury += $thisLine->getHeight(0) + $this->lineSpacing;
		}
		
		
		$this->doneDrawing = 1;
		return $cury - $y;
		*/
		
		$cury = $y;
		foreach(array_keys($lines) as $lineKey)
		{
			$thisLine = $lines[$lineKey];
			$curx = $x;
			$cury += 12;
			$thisLine->draw($curx, $cury, NULL, $reallyDraw);
		}
		
		return $cury - $y;
	}
	
	public function drawRenderTree($indentLevel = 0)
	{
		$tabs = '';
		for($i = 0; $i < $indentLevel; $i++)
			$tabs .= '&nbsp;&nbsp;&nbsp;&nbsp;';
		
		//	draw the open tag
		echo $tabs . '&lt;' . get_class($this) . '&gt;<br>';
		
		foreach(array_keys($this->members) as $lineKey)
		{
			$this->members[$lineKey]->drawRenderTree($indentLevel + 1);
		}
		
		//	draw the close tag
		echo $tabs . '&lt;/' . get_class($this) . '&gt;<br>';
	}
}
