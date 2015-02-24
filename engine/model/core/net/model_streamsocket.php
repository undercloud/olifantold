<?php
	namespace core\net;

		class Model_StreamSocket
		{
			private $handle = null;

			public function __construct($host,$port,$timeout = 30)
			{
				$errno  = null;
				$errstr = null;

				$this->handle = stream_socket_client("{$host}:{$port}", $errno, $errstr,$timeout);

				if(!$this->handle)
					throw new \app\exceptions\AppException($errstr,$errno);
			}

			public function setBlocking($mode)
			{
				stream_set_blocking($this->handle,$mode);
			}

			public function send($data)
			{
				return stream_socket_sendto($this->handle, $data);
			}

			public function receive($length = 1024)
			{
				$in = null;
				stream_set_timeout($this->handle, 10);
				while(feof($this->handle) === false)
					$in .= stream_socket_recvfrom($this->handle, $length);
				return $in;
			}

			public function __destruct()
			{
				if($this->handle)
					stream_socket_shutdown($this->handle,STREAM_SHUT_RDWR); 
			}
		}
?>