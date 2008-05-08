<?php
class Object
{
	private $mixins = array();
	private $getters = array();
	private $setters = array();
	private $mixinOwner;
	protected $allowAdhocAttributes = true;
	private $adhocAttributes = array();
	
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
	
	public function __isset($name)
	{
		trigger_error("I'm not even sure what this should do yet?  Do getters and setters count here or just adhoc attributes");
	}
	
	public function __unset($name)
	{
		trigger_error("I'm not even sure what this should do yet?  Do getters and setters count here or just adhoc attributes");
	}
	
	public function __get($name)
	{
		if(isset($this->getters[$name]))
		{
			$funcName = "get$name";
			return $this->$funcName();
		}
		
		if($this->allowAdhocAttributes && isset($this->adhocAttributes[$name]))
			return $this->adhocAttributes[$name];
		else
			trigger_error("attributes $name does not exist");
	}
	
	public function __set($name, $value)
	{
		if(isset($this->setters[$name]))
		{
			$funcName = "set$Name";
			$this->$funcName($value);
			return;
		}
		
		if(isset($this->getters[$name]))
			trigger_error("attributes $name is read only");
		else
		{
			if($this->allowAdhocAttributes)
				$this->adhocAttributes[$name] = $value;
			else
				trigger_error("attributes $name does not exist");
		}
	}
	
	protected function mixin($className, $params = NULL)
	{
		$this->mixins[$className] = new $className();
		$this->mixins[$className]->mixedInto($this);
		$this->mixins[$className]->init($params);
		return $this->mixins[$className];
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
