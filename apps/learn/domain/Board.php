<?php
class Board
{
	private $cells;
	const size = 15;
	
	function __construct()
	{
		$this->cells = array();
		for($row = 1; $row <= self::size; $row++)
		{
			$this->cells[$row] = array();
			for($col = 1; $col <= self::size; $col++)
			{
				$this->cells[$row][$col] = new Cell();
			}
		}
	}
	
	function setCellLetter($row, $col, $letter)
	{
		// echo "<strong>setting the cell</strong><br>";
		// echo_r($row);
		// echo_r($col);
		// echo_r($letter);
		$this->cells[(int)$row][(int)$col]->setLetter((string)$letter); 
	}
	
	function getSets()
	{
		$allWords = array();
		
		//	get them from the rows
		$curWord = '';
		$words = array();
		for($row = 1; $row <= 15; $row++)
		{
			$words[$row] = array();
			for($col = 1; $col <= 15; $col++)
			{
				$thisCell = $this->cells[$row][$col];
				if($thisCell->getLetter())
				{
					$curWord .= $thisCell->getLetter();
				}
				else
				{
					if($curWord)
					{
						$words[$row][] = $curWord;
						$curWord = '';
					}
				}
			}
			
			if($curWord)
			{
				$words[$row][] = $curWord;
				$curWord = '';
			}
		}
		$allWords['rows'] = $words;
		
		//	get them from the cols
		$curWord = '';
		$words = array();
		for($col = 1; $col <= 15; $col++)
		{
			$words[$col] = array();
			for($row = 1; $row <= 15; $row++)
			{
				// echo "row = $row; col = $col<br>";
				$thisCell = $this->cells[$row][$col];
				if($thisCell->getLetter())
				{
					$curWord .= $thisCell->getLetter();
					// echo "new letter; curword = $curWord<br>";
				}
				else
				{
					if($curWord)
					{
						$words[$col][] = $curWord;
						$curWord = '';
					}
				}
			}
			
			if($curWord)
			{
				$words[$col][] = $curWord;
				$curWord = '';
			}
		}
		$allWords['col'] = $words;
		
		// echo_r($allWords);
		
		return $allWords;
	}
}