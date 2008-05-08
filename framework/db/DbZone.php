<?php
class DbZone extends Object
{
	private $class;
	private $fieldInfo = array();
	private $orderby;
	private $conditions;
	
	public function init($params)
	{
		$this->getMixinOwner()->setBaseDir('dbzone');
		$this->class = $params['class'];
		if(isset($params['orderby']))
			$this->orderby = $params['orderby'];
		else
			$this->orderby = 'id';
		
		if(isset($params['conditions']))
			$this->conditions = $params['conditions'];
		else
			$this->conditions = null;
	}
	
	public function setFieldType($field, $type)
	{
		$this->fieldInfo[$field]['type'] = $type;
	}
	
	public function pageDefault($p, $z)
	{
		$this->redirect('list');
	}
	
	public function pageList($p, $z)
	{
		$table = DbObject::_getTableSchema($this->class);
		$objects = DbObject::_find($this->class, $this->conditions, array('orderby' => $this->orderby . ', id'));
		$this->assign('table', $table);
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
		else if($action == 'delete')
		{
			$object = new $this->class($_POST['id']);
			$object->destroy();
			Redirect();
		}
	}
	
	public function pageEdit($p, $z)
	{
		if(isset($p[1]) && $p[1])
			$object = new $this->class($p[1]);
		else
			$object = new $this->class();
		$object->forceFields();
		$this->assign('object', $object);
		$this->assign('fieldInfo', $this->fieldInfo);
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