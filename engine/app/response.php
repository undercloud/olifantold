<?php
	namespace app;
	/** class Responser
		@brief Отправка данных клиенту 
	*/
		class Response
		{
			public function __construct(){}
			
			public function prepare()
			{
				$res = new \stdClass();

				$res->status = 200;
				$res->statusText = 'OK';

				$res->header = array();
				
				$res->cookies = new \stdClass();
				$res->cookies->setup = array();
				$res->cookies->set = function()use($res){

				};

				$res->cookies->remove = function($name)use($res){

				};

				$res->body = null;

				return $res;
			}

			public function send($res)
			{
				header($_SERVER['SERVER_PROTOCOL'] . ' ' . $res->status . ' ' . $res->statusText);

				foreach($res->header as $key=>$value){
					header($key . ': ' . $value);
				}

				if(isset($res->body)){
					if(is_scalar($res->body)){
						$this->out($res->body);
					}else if(is_array($res->body) or is_object($res->body)){
						$this->sendJson($res->body);
					}
				}
			}

			public function out($data)
			{
				echo $data;
			}

			public function sendJson($data)
			{
				header('Content-Type: application/json; charset=utf-8');
				
				$encoded = json_encode(
					$data,
					JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_FORCE_OBJECT
				);

				$last_error = json_last_error();

				if($last_error == JSON_ERROR_NONE)
					return $this->out($encoded);
				else
					throw new \app\exceptions\AppException('Malformed JSON '.$last_error);
			}
		}
?>