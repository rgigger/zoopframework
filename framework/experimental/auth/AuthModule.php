<?php
class AuthModule extends ZoopModule
{
	private static $storageHandler;
	
	//	general module stuff
	function getIncludes()
	{
		return array();
	}
	
	function getClasses()
	{
		return array('AuthMethod', 'AuthMethodStandard', 'AuthStorageHandler', 'AuthStorageHandlerDb');
	}
	
	function configure()
	{
		//	get the proper strorage handler
		$storageInfo = $this->getConfig('storage');
		if($storageInfo['type'] != 'db')
			trigger_error("auth storage type not yet supported: " . $storageInfo['type']);		
		self::storageHandler = new AuthStorageHandlerDb($storageInfo);
	}	
	
	//	module specific stuff
	static public function initdb()
	{
		//	create the migration table if it does not exist
		$schema = SqlGetSchema();

		$table = $this->getConfig('table');
		$keyfield = $this->getConfig('keyfield');
		$passwordfield = $this->getConfig('passwordfield');
		$noncefield = $this->getConfig('noncefield');
		$typefield = $this->getConfig('typefield');

		if(!$schema->tableExists($table))
		{
			$sql = "create table $table (
						id serial primary key, 
						person_id int4 references person
						$keyfield text,
						$passwordfield text,
						$noncefield text,
						$typefield text)";
			SqlAlterSchema($sql);
		}
	}	
	
	static public function setPassword($username, $password)
	{
		//	get the proper AuthMethod and check the password
		$methodInfo = $this->getConfig('method');
		if($methodInfo['type'] != 'standard')
			trigger_error("auth method type not yet supported: " . $methodInfo['type']);
		
		$method = new AuthMethodStandard();
		$method->setPassword($username, $password);
	}
	
	static public function authenticate($username, $givenPassword)
	{
		//	get the user info and return a failure if it's not found
		$userInfo = self::storageHandler->getInfo($username);
		if(!$userInfo)
			return new AuthResult(AuthResult::failed, AuthResult::badUsername);
		
		//	get the proper AuthMethod and check the password
		if($method != 'standard')
			trigger_error("auth method type not yet supported: " . $method);
		
		$method = new AuthMethodStandard();
		return $method->checkPassword($password, $userInfo);
		*/
	}
}
