<?php
class HttpServer extends Pherver
{
	protected function handleNew($newsock)
	{
	}
	
	protected function handleRead($read_sock)
	{
		//	read up to 10000 bytes
		// $data = '';
		// while(true)
		// {
		// 	echo "getting line\n";
		// 	socket_set_nonblock($read_sock);
		// 	$line = socket_read($read_sock, 1024, PHP_BINARY_READ);
		// 	echo "type = " . gettype($line) . "\n";
		// 	echo "len = " . strlen($line) . "\n";
		// 	echo "value = $line\n";
		// 	
		// 	$err = socket_last_error($read_sock);
		// 	if($err)
		// 		echo "error = " . socket_strerror($err) . "\n";
		// 	else
		// 		echo "no error\n";
		// 	
		// 	if(strlen($line) == 0)
		// 		break;
		// 	$data .= $line;
		// }
		
		$data = socket_read($read_sock, 1024, PHP_BINARY_READ);
		
		
		// if(strlen($data) == 5)
		// 	for($i = 0; $i < strlen($data); $i++)
		// 		echo "the char is = " . ord($data[$i]) . "\n";
		
		echo "start dumping raw data\n";
		var_dump($data);
		echo "done dumping raw data\n";
		
		// check if the client is disconnected
		// if ($data === false)
		if(!$data)
		{
			// remove client for $this->clients array
			$key = array_search($read_sock, $this->clients);
			unset($this->clients[$key]);
			echo "client disconnected.\n";
			// continue to the next client to read from, if any
			return;
		}
		
		echo "recieving request\n";
		// check if there is any data after trimming off the spaces
		if (!empty($data))
		{
			echo "printing raw request content\n";
			echo $data . "\n";
			// 
			$message = new HttpMessage($data);
			
			echo "printing out message object\n";
			print_r($message);
			echo $message->getType() . "\n";
			echo $message->getRequestMethod() . "\n";
			echo $message->getRequestUrl() . "\n";
			
			HttpResponse::setData("<html><body>asdf</body></html>");
			
			print_r(HttpResponse::getRequestHeaders());
			
			// HttpResponse::send();
			// echo "printing the data\n";
			// echo HttpResponse::getData();
		}
		else
		{
			echo "received empty request\n";
			sleep(10);
		}
		echo "done receiving request\n\n\n\n";
	}
}
