<?php
class GraphicTextRun extends GraphicObject
{
	private $text;
	private $leftSpace;
	private $rightSpace;
	private $widthsCalced = false;
	private $length;
	
	function __construct($context)
	{
		parent::__construct($context);
		$this->text = '';
		$this->length = 0;
		$this->leftSpace = 0;
		$this->rightRight = 0;
		// $this->style = NULL;
		// $this->charWidths = array();
		// $this->totalWidth = -1;
	}
	
	public function setTextInfo($text)
	{
		//	consolidate white space.  Should this be done here or in the xml layer?
		//	this is really an xml thing not a graphics thing
		$content = ereg_replace("[[:space:]]+", ' ', $text['content']);		
		$this->text = $content;
		$this->length = strlen($this->text);
		$this->leftSpace = $text['leftTrim'];
		$this->rightSpace = $text['rightTrim'];
	}
	
	public function setStyle($style)
	{
		
	}
	
	function getLength()
	{
		return $this->length;
	}
	
	//
	//	the drawing functions
	//
	
	private function isBreakChar($inChar)
	{
		$breakChars = array(' ' , '-');
		if(in_array($inChar, $breakChars))
			return true;
		else
			return false;
	}
	
	private function calcCharWidths()
	{
		if($this->widthsCalced)
			return;
		// assert($this->style !== NULL);
		
		// $this->context->setTextFont($this->style);
		
		// echo_r($this->text);
		$this->charWidths = $this->context->getCharWidths($this->text);
		$this->totalWidth = array_sum($this->charWidths);
		
		// echo_r($this->charWidths);
		// echo_r($this->totalWidth);
		
		$this->widthsCalced = true;
	}
	
	function getPartWidth($start, $length)
	{
		// assert($this->style !== NULL);
		
		$total = 0;
		for($i = $start; $i < $start + $length; $i++)
			$total += $this->charWidths[$i];
		return $total;
		
		// assert($this->style !== NULL);
		// 
		// if($length == NULL)
		// 	$string = substr($this->text, $start);
		// else
		// 	$string = substr($this->text, $start, $length);
		// 
		// $this->context->setTextFont($this->style);
		// 
		// return $this->context->getStringWidth($string);
	}
	
	function getHeight()
	{
		return 12;
		// return $this->style->getTextSize() * $this->context->getLineHeightMultiplier();
	}
	
	//	this function tell us how much of the string will fit onto the current line
	function getFitsLength($startPos, $remainingWidth)
	{
		$this->calcCharWidths();
		
		$lastGoodPos = -1;
		$width = 0;
		$textLen = strlen($this->text);
		for($i = $startPos; $i < $textLen; $i++)
		{
			$charWidth = $this->charWidths[$i];
			
			if($width + $charWidth > $remainingWidth)
			{
				if($lastGoodPos == -1)
					return $i - $startPos;
				else
					return $lastGoodPos - $startPos + 1;
			}
			
			if($this->isBreakChar($this->text{$i}))
			{
				$lastGoodPos = $i;
			}
			
			$width += $charWidth;
		}
		
		return $textLen - $startPos;
	}
	
	function drawPart($x, $y, $start = 0, $length = NULL)
	{
		// assert($this->style !== NULL);
		// echo "GraphicTextRun::drawPart : $x, $y, $start, $length<br>";
		
		$height = $this->getHeight();
		
		/*
		switch($height)
		{
			case 14:
				$tweak = $height * 0.12;
				break;
			default:
				$tweak = 0;
				break;
		}
		*/
		
		// $y = $y - $tweak;
		
		if($length == NULL)
			$string = substr($this->text, $start);
		else
			$string = substr($this->text, $start, $length);
		
		// $this->context->setTextColor($this->style->color[0], $this->style->color[1], $this->style->color[2]);
		// $this->context->setTextFont($this->style);
		$this->context->addText($x, $y + $this->getHeight(), $string);
	}
	
	public function draw($x, $y, $width, $reallyDraw = true)
	{
		trigger_error("We don't actually use draw, we use draw part.");
	}
	
	
	//
	//	debug stuff
	//
	
	public function getObjectTree($indentLevel = 0)
	{
		$tabs = '';
		for($i = 0; $i < $indentLevel; $i++)
			$tabs .= '&nbsp;&nbsp;&nbsp;&nbsp;';
		
		//	draw the close tag
		echo $tabs . $this;
	}
	
	
	public function drawRenderTree($indentLevel = 0)
	{
		$tabs = '';
		for($i = 0; $i < $indentLevel; $i++)
			$tabs .= '&nbsp;&nbsp;&nbsp;&nbsp;';
		
		//	draw the close tag
		echo $tabs . $this;
	}
	
	
	public function __toString()
	{
		return '&lt;GraphicTextRun text=" ' . $this->text . '"/&gt;<br>';
	}
	
	/*
	var $style;
	var $breakPoints;
	var $charWidths;
	var $totalWidth;
		
	function getUnwrappedWidth()
	{
		assert($this->style !== NULL);
		assert($this->totalWidth > -1);
		return $this->totalWidth;
	}
	
	function getWidth()
	{
		assert($this->style !== NULL);
		assert($this->totalWidth > -1);
		return $this->totalWidth;
		
		// $this->context->setTextFont($this->style);
		// return $this->context->getStringWidth($this->text);
	}
	
	function setText($text)
	{
		assert($this->style !== NULL);
		
		//	we need to get rid of the excess white space in the middle
		
		$content = ereg_replace("[[:space:]]+", ' ', $text['content']);
		
		$this->text = $content;
		
		//echo 'START' . $this->text . "END<br>\n";
		
		$this->leftSpace = $text['leftTrim'];
		$this->rightSpace = $text['rightTrim'];
		
		$this->calcCharWidths();
	}
	
	function calcCharWidths()
	{
		assert($this->style !== NULL);
		
		$this->context->setTextFont($this->style);
		
		$len = strlen($this->text);
		
		$totalWidth = 0;
		for($i = 0; $i < $len; $i++)
		{
			if($this->context->useKerningHack())
			{
				if($i == $len - 1)
				{
					$width = $this->context->getStringWidth(substr($this->text, $i, 1));	//	would it be better to use $this->text{$i} here?
					$totalWidth += $width;
					$this->charWidths[$i] = $width;				
				}
				else
				{
					$thisAndNextWidth = $this->context->getStringWidth(substr($this->text, $i, 2));
					$justNextWidth = $this->context->getStringWidth(substr($this->text, $i+ 1, 1));
					$width = $thisAndNextWidth - $justNextWidth;
					
					$totalWidth += $width;
					$this->charWidths[$i] = $width;
				}
			}
			else
			{
				$width = $this->context->getStringWidth(substr($this->text, $i, 1));	//	would it be better to use $this->text{$i} here?
				$totalWidth += $width;
				$this->charWidths[$i] = $width;
			}
		}
		
		$this->totalWidth = $totalWidth;
	}
			
	function addLeftSpace()
	{
		$this->text = ' ' . $this->text;
	}
	
	function getLeftSpace()
	{
		return $this->leftSpace;
	}
	
	function getRightSpace()
	{
		return $this->rightSpace;
	}
	
	function setStyle(&$style)
	{
		assert( is_a($style, 'GraphicTextStyle') );
		
		$this->style = $style;
	}
	
	function isInline()
	{
		return 1;
	}
	
	function draw($x, $y)
	{
		assert($this->style !== NULL);
		
		$this->context->setTextColor($this->style->color[0], $this->style->color[1], $this->style->color[2]);
		$this->context->setTextFont($this->style);
		$this->context->addText($x, $y + $this->getHeight(), $this->text);
	}
	
	function __toString()
	{
		$s = "<" . get_class($this) . ">";
		$s .= '~' . $this->text . '~';
		$s .= "</" . get_class($this) . ">";
		return $s;
	}
	*/
}
