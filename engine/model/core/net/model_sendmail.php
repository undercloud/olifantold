<?php
	namespace core\net;
	
		class Model_SendMail
		{
			private $to      = null;
			private $subject = null;
			private $body    = null;
			private $headers = null;
			private $params  = null;
			private $files   = null;
			private $mime    = null;
			
			public $boundary = null;

			public function __construct()
			{
				$this->to       = array();
				$this->headers  = array();
				$this->files    = array();
				$this->boundary = "--".strtoupper(md5(uniqid(rand())));
			}

			public function addReceiver($receiver)
			{
				$this->to[] = $receiver;
			}

			public function setSubject($subject)
			{
				$this->subject = $subject;
			}

			public function setBody($body,$mime = 'text/plain')
			{
				$this->body = $body;
				$this->mime = $mime;
			}

			public function addFile($path)
			{
				$handle = fopen($path,"rb");   
			    if (!$handle)
			    	throw new \app\exceptions\AppException("File ".$path." not found");

			    $file_content = fread($handle, filesize($path));   
			    fclose($handle);

			    $file_name = basename($path);
			    $this->files[$file_name] = $file_content;
			}

			public function addHeader($header)
			{
				$this->headers[] = $header;
			}

			public function setParams($params)
			{
				$this->params = $params;
			}

			public function send()
			{
				$EOL = "\n";

				$this->addHeader("Content-Type: multipart/mixed; boundary=\"".$this->boundary."\"");

				$this->body = 
					"--".$this->boundary.$EOL.
					"Content-Type:".$this->mime."; charset=utf-8".$EOL.
				    "Content-Transfer-Encoding: base64".$EOL.$EOL.
					chunk_split(base64_encode($this->body)).$EOL;

				if(count($this->files)){
				    foreach($this->files as $filename => $filecontent){
				       $this->body .= "--".$this->boundary.$EOL.
				            "Content-Type: application/octet-stream; name=\"".$filename."\"".$EOL.
				            "Content-Transfer-Encoding: base64".$EOL.
				            "Content-Disposition:attachment; filename=\"".$filename."\"".$EOL.$EOL.
				            chunk_split(base64_encode($filecontent)).$EOL;
				    }
				}

				$this->body .= "--".$this->boundary."--".$EOL; 

				return mail(
					implode(",", $this->to), 
					$this->subject, 
					$this->body,
					implode($EOL,$this->headers),
					$this->parameters
				);
			}
		}

		/*
		
		-- USAGE -- 

		$sm = new Model_SendMail();

		$sm->addReceiver("alexandrostruses@gmail.com");
		$sm->addReceiver("alexandrostruses@yandex.ru");
		$sm->addReceiver("guitargot@mail.ru");

		$sm->setSubject("Текст на русском");

		$sm->addHeader("MIME-Version: 1.0;");	
		$sm->addHeader("From: Lololo<alexandrostruses@yandex.ru>");
		$sm->addHeader("X-Mailer: PHP/" . phpversion());

		$sm->setBody("Русский текст");
		$sm->addFile("log.txt");
		$sm->addFile("robots.txt");
		$sm->addFile("live-3a5faa33.txt");

		$sm->send();
		*/
?>