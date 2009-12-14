<?php
class Message
{
	var $gui;
	
	function message($gui = NULL)
	{
		if($gui)
			$this->gui = $gui;
		else
			$this->gui = new gui();		
	}
	
	function setLanguageId($languageId)
	{
		$this->gui->setLanguageId($languageId);
	}
	
	function assign($key, $value)
	{
		$this->gui->assign($key, $value);
	}
	
	function getTranslation($tpl_file, $name)
	{
		return $this->gui->getTranslation($tpl_file, $name);
	}
	
	function sendText($template, $to, $toName, $from, $fromName, $subject)
	{
		$body = $this->gui->fetch($template);
		
		mail::text($to, $toName, $from, $fromName, $subject, $body);
	}
	
	function sendHtml($template, $to, $toName, $from, $fromName, $subject)
	{
		$body = $this->gui->fetch($template);
		
		mail::html($to, $toName, $from, $fromName, $subject, $body);
	}
}