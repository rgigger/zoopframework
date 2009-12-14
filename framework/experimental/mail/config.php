<?php
DefineOnce('mail_method', 'smtp');
DefineOnce('mail_smtp_auth_use', 0);


switch(mail_method)
{
	case 'smtp':
		RequireDefined('mail_smtp_host');
		
		if(mail_smtp_auth_use)
		{
			RequireDefined('mail_smtp_auth_username');
			RequireDefined('mail_smtp_auth_password');
		}
		break;
	default:
		trigger_error('undefined transport method: ' . mail_method);
		break;
}
