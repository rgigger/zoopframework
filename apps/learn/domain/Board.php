<?php
class Board extends DbObject
{
	private $cells;
	const size = 15;
	
	function __construct($init)
	{
		parent::__construct($init);
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
	
	public function draw()
	{
		echo '<form action="' . virtual_url . '" method="POST">';
		echo "<table border=\"1\">";
		for($row = 1; $row <= self::size; $row++)
		{
			echo "<tr>\n";
			for($col = 1; $col <= self::size; $col++)
			{
				echo "<td>";
				$this->cells[$row][$col]->draw($row, $col);
				echo "</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
		echo '<input type="submit">';
		echo '</form>';
	}
	
	public function loadCells()
	{
		$rows = SqlFetchRows("select * from board_cell where board_id = :boardId", array('boardId' => $this->id));
		foreach($rows as $thisRow)
		{
			$this->setCellLetter($thisRow['row_num'], $thisRow['col_num'], $thisRow['letter']);
		}
	}
	
	public function saveCells($cells)
	{
		SqlBeginTransaction();
		SqlDeleteRows("delete from board_cell where board_id = :boardId", array('boardId' => $this->id));
		foreach($cells as $rowNum => $thisRow)
		{
			foreach($thisRow as $colNum => $letter)
			{
				if($letter)
				{
					SqlInsertRow("insert into board_cell (board_id, row_num, col_num, letter)
									values (:boardId, :rowNum, :colNum, :letter)", 
									array('boardId' => $this->id, 'rowNum' => $rowNum,
											'colNum' => $colNum, 'letter' => trim(strtoupper($letter))));
				}
			}
		}
		SqlCommitTransaction();
	}
	
	public function getWords()
	{
		$words = array();
		
		for($row = 1; $row <= self::size; $row++)
		{
			$word = '';
			for($col = 1; $col <= self::size; $col++)
			{
				$letter = $this->cells[$row][$col]->getLetter();
				if($letter)
					$word .= $letter;
				else if($word)
				{
					$words[$word] = 1;
					$word = '';
				}
			}
			
			if($word)
			{
				$words[$word] = 1;
				$word = '';
			}
		}
		
		for($col = 1; $col <= self::size; $col++)
		{
			$word = '';
			for($row = 1; $row <= self::size; $row++)
			{
				$letter = $this->cells[$row][$col]->getLetter();
				if($letter)
					$word .= $letter;
				else if($word)
				{
					$words[$word] = 1;
					$word = '';
				}
			}
			
			if($word)
			{
				$words[$word] = 1;
				$word = '';
			}
		}
		// SqlEchoOn();
		foreach($words as $thisWord => $thing)
		{
			SqlBeginTransaction();
			$word = strtoupper($thisWord);
			$len = strlen($word);
			if($len < 2)
				continue;
			$id = SqlFetchCell("select id from word where word = :word", array('word' => $word));
			if(!$id)
			{
				echo "inserting word: $word<br>";
				SqlInsertRow("insert into word (word, len) values (:wordwrap, :len)", array('word' => $word, 'len' => $len));
				Learn::generateWordLetters($word);
			}
			SqlCommitTransaction();
		}
	}
}