<?php
class GraphicSoftBrokenLine extends GraphicObject
{
	var $entries;
	var $width;
	
	function GraphicSoftBrokenLine()
	{
		$this->entries = array();
		$this->width = 0;
	}
	
	function addEntry($member, $start = 0, $length = NULL)
	{
		$newEntry['member'] = $member;
		$newEntry['start'] = $start;
		$newEntry['length'] = $length;
		$this->entries[] = $newEntry;
		
		// $this->width += $member->getPartWidth($start, $length);
	}
	
	function getWidth()
	{
		return $this->width;
	}
	
	function draw($x, $y, $width, $reallyDraw = 1)
	{
		$curx = $x;
		$cury = $y;
		
		$height = 12;
		
		foreach(array_keys($this->entries) as $entryKey)
		{
			$thisEntry = $this->entries[$entryKey];
			
			assert($thisEntry['member']->isInline());
			if($reallyDraw)
			{
				$thisEntry['member']->drawPart($curx, $cury, $thisEntry['start'], $thisEntry['length']);
				$curx += $thisEntry['member']->getPartWidth($thisEntry['start'], $thisEntry['length']);
			}
		}
		/*
		if($reallyDraw)
			$largestHeight = $this->getHeight($width);
		
		$cury = $y;
		$height = 0;
		foreach(array_keys($this->entries) as $entryKey)
		{
			$thisEntry = $this->entries[$entryKey];
			
			assert($thisEntry['member']->isInline());
			
			if($reallyDraw)
			{
				//	if we need to draw the whole object
				if($thisEntry['start'] == 0 and $thisEntry['length'] === NULL)
				{
					trigger_error("I don't think that this actually ever gets called.  It's all drawPart, we should remove this section");
					$thisEntry['member']->draw($curx, $cury + ($largestHeight - $thisEntry['member']->getHeight()));
					$curx = $thisEntry['member']->getWidth();
				}
				else
				{
					// echo $thisEntry['member'];
					$thisEntry['member']->drawPart($curx, $cury + ($largestHeight - $thisEntry['member']->getHeight()), 
													$thisEntry['start'], $thisEntry['length']);
					$curx += $thisEntry['member']->getPartWidth($thisEntry['start'], $thisEntry['length']);
				}
			}
			
			if($thisEntry['member']->getHeight() > $height)
			{
				$height = $thisEntry['member']->getHeight();
			}
		}
		*/
		
		return $height;
	}
}
