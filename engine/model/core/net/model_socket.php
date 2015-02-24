<?php
	namespace core\net;
	
		class Model_Socket
		{
			private $socket_handle = null;
			
			private $host = null;
			private $port = null;

			private $error_number = 0;
			private $error_string = '';

			const SOCKET_TIMEOUT = 10;
		
			public function __construct($host,$port)
			{
				$this->host = $host;
				$this->port = $port;
			}
			
			public function connectToServer($permanent = false)
			{
				$this->socket_handle = ($permanent) ? pfsockopen(
					$this->host,
					$this->port,
					$this->error_number,
					$this->error_string,
					self::SOCKET_TIMEOUT
				) 
				:
				fsockopen(
					$this->host,
					$this->port,
					$this->error_number,
					$this->error_string,
					self::SOCKET_TIMEOUT
				);
			}

			public function isConnected()
			{
				return ($this->socket_handle != null) ? true : false;
			}
			
			public function send($data)
			{
				$total = fwrite($this->socket_handle,$data,mb_strlen($data));
				fflush($this->socket_handle);
				return $total;
			}

			public function receive($timeout = 10,$buffer = 1024)
			{
				stream_set_timeout($this->socket_handle, $timeout); // timeout для чтения
				
				$response = null;
				$start    = null;

				while(feof($this->socket_handle) !== true){
					$response .= fread($this->socket_handle, $buffer); 
					$stream_meta_data = stream_get_meta_data($this->socket_handle);
					if($stream_meta_data['unread_bytes'] == 0)
						break;
				}
				return $response;
			}

			public function errorNumber()
			{
				return $this->error_number;
			}

			public function errorText()
			{
				return $this->error_string;
			} 

			public function closeConnection()
			{
				if($this->socket_handle)
					fclose($this->socket_handle);
			}
		}
?>