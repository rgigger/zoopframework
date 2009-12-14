<?php
class Mailer extends PHPMailer
{
	function Mailer()
	{
		$this->PluginDir = zoop_dir . '/phpmailer/';
		$this->SetLanguage('en', zoop_dir . '/phpmailer/languages/');
		
		if(mail_method == 'smtp')
		{
			$this->IsSMTP();
			$this->Host = mail_smtp_host;  
			$this->SMTPAuth = mail_smtp_auth_use;
			if(mail_smtp_auth_use)
			{
				$this->Username = mail_smtp_auth_username;
				$this->Password = mail_smtp_auth_password;
			}
		}
		else
			assert(false);
		
		$this->CharSet = 'UTF-8';
	}
}