<?php
	namespace core\net;
	
		class Model_Curl
		{
			private $handle = null;
			
			public function __construct($url)
			{
				$this->handle = curl_init($url);
			}
			
			public function setOption($option,$value)
			{
				return curl_setopt($this->handle,$option,$value);
			}
			
			public function sendQuery()
			{
				return curl_exec($this->handle);
			}
			
			public function getInfo($option = 0)
			{
				return ($option) ? curl_getinfo($this->handle,$option) : curl_getinfo($this->handle);
			}
			
			public function hasError()
			{
				return (curl_errno($this->handle) != 0) ? true : false;
			}
			
			public function errorCode()
			{
				return curl_errno($this->handle);
			}
			
			public function errorMessage()
			{
				return curl_error($this->handle);
			}

			public function prepareRequest($request)
			{
				$data = array();
				foreach($request as $key=>$value){
					if(is_array($value)){
						$data = array_merge($this->getPOSTArray($key , $value) , $data);
					}else{
						$data[$key]=$value;
					}
				}
				return $data;
			}
		
			private function getPOSTArray($key, $array){
				$result = array();
				foreach($array as $filed=>$data){
					if(is_array($data)){
						$result = array_merge($result , $this->getPOSTArray($key."[$filed]" , $data));
					}else{
						$result[$key."[$filed]"] = $data;
					}
				}
				return $result;
			}

			public function __destruct()
			{
				curl_close($this->handle);
			}
		}
?>