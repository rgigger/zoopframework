<?php
class Cell
{
	private $letter;
	
	function setLetter($leter)
	{
		$this->letter = $leter;
	}
	
	function getLetter()
	{
		return $this->letter;
	}
}