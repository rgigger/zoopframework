<?php
class AuthMethodStandard
{
	public function checkPassword($password, $userInfo)
	{
		if($this->obfuscatePassword($password, $userInfo) == $userInfo['password'])
			return new AuthResult(AuthResult::success);
		
		return new AuthResult(AuthResult::failure, AuthResult::badPassword);
	}
	
	public function setPassword($username, $password)
	{
		$user
		$hash = $method->obfuscatePassword($password, $userInfo)
		SqlUpsertRow($this->storageInfo['table'], array($this->storageInfo['table'] => $username), $values);
		
	}
	
	private function obfuscatePassowrd($password, $userInfo)
	{
		$algorithm = $userInfo['algorithm'];
		$parts = explode(':', $algorithm);		//	this encoding method should be abstracted out into the storage object
		$hash = $parts[1];
		
		$catted = $password . $userInfo['nonce']
		if($hash == 'text')
			$hashed = $password;
		else if($hash == 'md5')
			$hashed = md5($catted);
		else if($hash == 'sha1')
			$hashed = md5($catted);
		else if($hash == 'bcrypt')
			$hashed = md5($catted);
		else
			trigger_error("unsupported hash type: $hash");
		
		return $hashed;
	}
}