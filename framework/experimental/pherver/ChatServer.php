<?php
class ChatServer extends Pherver
{
	protected function handleNew($newsock)
	{
		// send the client a welcome message
		socket_write($newsock, "no noobs, but ill make an exception :)\n".
		"There are ".(count($this->clients) - 1)." client(s) connected to the server\n");

		socket_getpeername($newsock, $ip);
		echo "New client connected: {$ip}\n";
	}
	
	protected function handleRead($read_sock)
	{
		// read until newline or 1024 bytes
		// socket_read while show errors when the client is disconnected, so silence the error messages
		$data = @socket_read($read_sock, 1024, PHP_NORMAL_READ);

		// check if the client is disconnected
		if ($data === false)
		{
			// remove client for $this->clients array
			$key = array_search($read_sock, $this->clients);
			unset($this->clients[$key]);
			echo "client disconnected.\n";
			// continue to the next client to read from, if any
			continue;
		}

		// trim off the trailing/beginning white spaces
		$data = trim($data);

		// check if there is any data after trimming off the spaces
		if (!empty($data))
		{	
			// send this to all the clients in the $this->clients array (except the first one, which is a listening socket)
			foreach ($this->clients as $send_sock)
			{
				// if its the listening sock or the client that we got the message from, go to the next one in the list
				if ($send_sock == $this->sock || $send_sock == $read_sock)
					continue;

				// write the message to the client -- add a newline character to the end of the message
				socket_write($send_sock, $data."\n");
			}
		}
	}
}
