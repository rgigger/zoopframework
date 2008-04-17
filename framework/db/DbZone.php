<?php
class DbZone extends Object
{
	private $class;
	
	public function init($params)
	{
		$this->getMixinOwner()->setBaseDir('dbzone');
		$this->class = $params['class'];
	}
	
	public function pageDefault($p, $z)
	{
		$this->redirect('list');
	}
	
	public function pageList($p, $z)
	{
		$objects = DbObject::_find($this->class, null, array('orderby' => 'id'));
		$this->assign('objects', $objects);
	}
	
	public function postList($p, $z)
	{
		$action = $_POST['action'];
		if($action == 'edit')
			$this->redirect('edit/' . $_POST['id']);
		else if($action == 'view')
			$this->redirect('view/' . $_POST['id']);
		else if($action == 'add')
			$this->redirect('edit');
	}
	
	public function pageEdit($p, $z)
	{
		if(isset($p[1]) && $p[1])
			$object = new $this->class($p[1]);
		else
			$object = new $this->class();
		$object->forceFields();
		$this->assign('object', $object);
	}
	
	public function postEdit($p, $z)
	{
		if(isset($p[1]) && $p[1])
			$object = new $this->class($p[1]);
		else
			$object = new $this->class();
		
		foreach($_POST['fields'] as $field => $value)
			$object->$field = $value;
		$object->save();
		$this->redirect('list');
	}
	
	public function pageView($p, $z)
	{
		$object = new $this->class($p[1]);
		$object->forceFields();
		$this->assign('object', $object);
	}
}

AddTemplateDir(dirname(__file__) . '/templates');