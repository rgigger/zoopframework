<?php
class Object
{
	private $mixins = array();
	private $getters = array();
	private $setters = array();
	private $mixinOwner;
	
	protected function addGetter($name)
	{
		$this->getters[$name] = $name;
	}
	
	protected function addSetter($name)
	{
		$this->setters[$name] = $name;		
	}
	
	protected function addGetSetter($name)
	{
		$this->addGetter($name);
		$this->addSetter($name);
	}
	
	public function __get($name)
	{
		if(isset($this->getters[$name]))
		{
			$funcName = "get$name";
			return $this->$funcName();
		}
	}
	
	public function __set($name, $value)
	{
		if(isset($this->setters[$name]))
		{
			$funcName = "set$Name";
			$this->$funcName($value);
		}
	}
	
	protected function mixin($className, $params = NULL)
	{
		$this->mixins[$className] = new $className();
		$this->mixins[$className]->mixedInto($this);
		$this->mixins[$className]->init($params);
	}
	
	protected function mixedInto($mixer)
	{
		$this->mixinOwner = $mixer;
		$this->mixins[get_class($mixer)] = $mixer;
	}
	
	protected function getMixinOwner()
	{
		return $this->mixinOwner;
	}
	
	public function _methodExists($methodName)
	{
		if(method_exists($this, $methodName))
			return true;
		
		foreach($this->mixins as $className => $thisMixin)
		{
			$class = new ReflectionClass($className);
			if($class->hasMethod($methodName))
				return true;
		}
		
		return false;
	}
	
	public function __call($methodName, $args)
	{
		foreach($this->mixins as $className => $thisMixin)
		{
			$class = new ReflectionClass($className);
			if($class->hasMethod($methodName))
			{
				call_user_func_array(array($thisMixin, $methodName), $args);
				return;
			}
		}
		
		trigger_error("Method '$methodName' not found");
	}
}
