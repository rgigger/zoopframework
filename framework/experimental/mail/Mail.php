<?php
//	these should really call a common function
//	or there shoudl be just one function with another paramater or something
class Mail
{
	function text($to, $toName, $from, $fromName, $subject, $body)
	{
		$mail = new mailer();
		if( defined('mail_from') )
		{
			$mail->From = mail_from;
			$mail->AddReplyTo($from, $fromName);
		}
		else
		{
			$mail->From = $from;
		}
		
		$mail->FromName = $fromName;
		
		$mail->AddAddress($to, $toName);

		$mail->IsHTML(false);

		$mail->Subject = $subject;
		$mail->Body    = $body;
		
		$result = $mail->Send(); 
		
		if(!$result)
		   trigger_error("Message could not be sent. Mailer Error: " . $mail->ErrorInfo);
	}
	
	function html($to, $toName, $from, $fromName, $subject, $body)
	{
		$mail = new mailer();
		
		if( defined('mail_from') )
		{
			$mail->From = mail_from;
			$mail->AddReplyTo($from, $fromName);
		}
		else
		{
			$mail->From = $from;
		}
		
		$mail->FromName = $fromName;
		
		$mail->IsHTML(true);
		$mail->Subject = $subject;
		$mail->Body    = $body;

		switch(gettype($to))
		{
			case 'string':
				$mail->AddAddress($to, $toName);
				break;
			case 'array':
				foreach($to as $toKey => $thisTo)
					$mail->AddAddress($thisTo, $toName[$toKey]);
				break;
			default:
				trigger_error('invalid type for address field');
		}			
		
		
		if(!$mail->Send())
		{
		   trigger_error("Message could not be sent. Mailer Error: " . $mail->ErrorInfo);
		}
	}
}