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
	
	public function draw($row, $col)
	{
		echo $this->letter ? '<textarea rows="1" cols="2" name="cell[' . $row . '][' . $col . ']">' . $this->letter . '</textarea>'
				: '<textarea rows="1" cols="2" name="cell[' . $row . '][' . $col . ']"></textarea>';
	}
}