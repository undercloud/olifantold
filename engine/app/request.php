<?php
	namespace app;
		/** @class Request
			@brief Обработка URI запроса
			@details Методы для работы с URI запросом
		*/
		class Request
		{
			private $request_uri = null;

			/** Инициализация
				@param $ru REQUEST_URI
				@return объект Request
			*/
			public function __construct($ru)
			{
				if(null === $ru or !is_string($ru))
					throw new \app\exceptions\AppException('Invalid Request URI');

				$this->request_uri = $ru;
			}

			/** Получение строки URI
				@return строка URI
			*/
			public function getURI()
			{
				return $this->request_uri;
			}

			/** Проверка что запрос сделан с помощью AJAX
				@return true если ajax запрос иначе false
			*/
			public static function isAjax()
			{
				return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
						and 
						strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
			}

			/** Перевод JSON запроса в массив
				@return array
			*/
			public static function fromJson()
			{
				return json_decode(file_get_contents('php://input'),true);
			}

			/** Проверка POST запроса на переполнение
				@return true или false
			*/
			public static function isPostOverflow()
			{
				return ($_SERVER['REQUEST_METHOD'] == 'POST' && 
					empty($_POST) &&
     				empty($_FILES) && 
     				$_SERVER['CONTENT_LENGTH'] > 0);
			}

			/** Удаление из URI ключа запроса
				@param $mapkey ключ запроса
				@return null
			*/
			public function excludeMapKey($mapkey)
			{
				$this->request_uri = str_replace(stripslashes(trim($mapkey,' /')),null, $this->request_uri);
			}

			/** Разбор строки URI
				@return array разобранная строка
			*/
			public function parse()
			{
				$request = trim($this->request_uri,' /');
				$pos = strpos($request,'?');
				if($pos !== false)
					$request = substr($request,0,$pos);

				$splitted = explode('/',$request);
				return array_filter($splitted,function($v){
					return ($v != '');
				}); 
			}

			/** Обработка суперглобальных переменных GET POST REQUEST COOKIE
				@return null
			*/
			public function prepareGlobalVars()
			{
				if(function_exists('get_magic_quotes_gpc') and get_magic_quotes_gpc()){
					function stripslashes_gpc(&$value){
						$value = stripslashes($value);
					}

					array_walk_recursive($_GET, 'stripslashes_gpc');
					array_walk_recursive($_POST, 'stripslashes_gpc');
					array_walk_recursive($_COOKIE, 'stripslashes_gpc');
					array_walk_recursive($_REQUEST, 'stripslashes_gpc');
				}
			}

			/** Изменение порядка ключей массива FILES
				@return null
			*/
			public function reorderFiles()
			{
				if(!isset($_FILES) or count($_FILES) == 0)
					return;

				$temp = array();

				foreach($_FILES as $key=>$item){
					if(is_array($_FILES[$key]['name'])){
						for($i = 0;$i < count($_FILES[$key]['name']);$i++){
							if($_FILES[$key]['name'][$i])
								$temp[$key][] = array(
									"name"     => $_FILES[$key]['name'][$i],
									"type"     => $_FILES[$key]['type'][$i],
									"tmp_name" => $_FILES[$key]['tmp_name'][$i],
									"error"    => $_FILES[$key]['error'][$i],
									"size"     => $_FILES[$key]['size'][$i]
								);
						}
					}else{
						$temp[$key] = $item;
					}
				}

				$_FILES = $temp;
			}
		}
?>