<?php
class ZoneDefault extends GuiZone
{
	function pageDefault()
	{
		$this->redirect('list');
//		$parser = new TodoListParser();
//		$todoList = $parser->parseFile('/Users/rick/Documents/todo/general.todo');
//		print_r($root);
	}
	
	function pageList()
	{
		$todoListCollection = new TodoListCollection(app_todo_dir);
		$fileNames = $todoListCollection->getTodoListNames();
		$this->assign('fileNames', $fileNames);
		$this->display('list');
	}
	
	function pageView($p)
	{
		$file = $p[1];
		$this->assign('filename', $file);
		$this->display('view');
	}
}