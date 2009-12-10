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
	
	public function &__get($name)
	{
		if(isset($this->getters[$name]))
		{
			$funcName = "get$name";
			return $this->$funcName();
		}
		
		if($this->mixinOwner && property_exists($this->mixinOwner, $name))
			return $this->mixinOwner->$name;
		
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
		
		if($this->mixinOwner && property_exists($this->mixinOwner, $name))
		    return $this->mixinOwner->$name = $value;
		
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
		if(isset($this->mixins[$className]))
			return;
		
		$this->mixins[$className] = new $className();
		$this->mixins[$className]->mixedInto($this);
		if(method_exists($this->mixins[$className], 'init'))
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
		// $t = microtime(1);
		// foreach($this->mixins as $className => $thisMixin)
		// 	echo get_class($this) . " $className $methodName $t<br>";
		
		foreach($this->mixins as $className => $thisMixin)
		{
			//	what is faster, ReflectionClass or method_exists
			$class = new ReflectionClass($className);
			if($class->hasMethod($methodName))
			{
				return call_user_func_array(array($thisMixin, $methodName), $args);
			}
		}
		
		trigger_error("Method '$methodName' not found");
	}
}
