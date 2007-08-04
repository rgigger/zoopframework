<?php
class Yaml
{
	static function read($filename)
	{
		return Spyc::YAMLLoad($filename);
	}
}