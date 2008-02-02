<?php
class Learn
{
	static public function regenerateAllWordLetters()
	{
		SqlDeleteRows('delete from word_letter', array());
		$words = SqlFetchRows("select * from word", array());
		SqlEchoOn();
		foreach($words as $thisWord)
		{
			Learn::generateWordLetters($thisWord);
		}
	}
	
	static public function generateWordLetters($thisWord)
	{
		$letters = array();
		for($i = 0; $i < $thisWord['len']; $i++)
		{
			if(isset($letters[$thisWord['word'][$i]]))
				$letters[$thisWord['word'][$i]]++;
			else
				$letters[$thisWord['word'][$i]] = 1;
		}
		
		foreach($letters as $letter => $count)
		{
			$sql = "insert into word_letter (word_id, letter, count) values (:wordId, :letter, :count)";
			SqlInsertRow($sql, array('wordId' => $thisWord['id'], 'letter' => $letter, 'count' => $count));
		}
	}
}