<?
class ZoneDefault extends zone
{
	function pageDefault()
	{
		$parser = new TodoListParser();
		$root = $parser->parseFile('/Users/rick/Documents/todo/general.todo');
		
//		print_r($root);
	}
}