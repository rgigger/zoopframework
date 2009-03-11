<?php
//	this originated from an example on the php website http://us2.php.net/socket_select
abstract class Pherver
{
	protected $clients, $sock;
	
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
			echo "starting the loop\n";
			// create a copy, so $this->clients doesn't get modified by socket_select()
			$read = $this->clients;

			// get a list of all the clients that have data to be read from
			// if there are no clients with data, go to next iteration
			echo "selecting a socket\n";
			
			$nchanged = socket_select($read, $write = NULL, $except = NULL, 10);
			
			if($nchanged === false)
			{
				echo "socket_select() failed, reason: " . socket_strerror(socket_last_error()) . "\n";
				continue;
			}
			
			if($nchanged < 1)
				continue;
			
			echo "printing readable sockets\n";
			print_r($read);

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
	
	abstract protected function handleNew($newsock);
	abstract protected function handleRead($read_sock);	
}