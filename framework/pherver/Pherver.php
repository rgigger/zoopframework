<?php
//	this originated from an example on the php website http://us2.php.net/socket_select
class Pherver
{
	private $clients, $sock;
	
	function listen($host, $port)
	{
		$port = 9050;

		// create a streaming socket, of type TCP/IP
		$this->sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

		// set the option to reuse the port
		socket_set_option($this->sock, SOL_SOCKET, SO_REUSEADDR, 1);

		// "bind" the socket to the address to "localhost", on port $port
		// so this means that all connections on this port are now our resposibility to send/recv data, disconnect, etc..
		socket_bind($this->sock, $host, $port);

		// start listen for connections
		socket_listen($this->sock);
		
		return $this->sock;
	}
	
	function run($host, $port)
	{
		//	listen on localhost:9050	
		$this->sock = Pherver::listen($host, $port);
		
		// create a list of all the clients that will be connected to us..
		// add the listening socket to this list
		$this->clients = array($this->sock);

		while (true)
		{
			// create a copy, so $this->clients doesn't get modified by socket_select()
			$read = $this->clients;

			// get a list of all the clients that have data to be read from
			// if there are no clients with data, go to next iteration
			if (socket_select($read, $write = NULL, $except = NULL, 0) < 1)
				continue;

			// check if there is a client trying to connect
			if(in_array($this->sock, $read))
			{
				// accept the client, and add him to the $this->clients array
				$this->clients[] = $newsock = socket_accept($this->sock);
				
				$this->handleNew($newsock);

				// remove the listening socket from the clients-with-data array
				$key = array_search($this->sock, $read);
				unset($read[$key]);
			}

			// loop through all the clients that have data to read from
			foreach ($read as $read_sock)
			{
				$this->handleRead($read_sock);
			}
		}
		
		socket_close($this->sock);
	}
	
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