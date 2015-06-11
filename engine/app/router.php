<?php
	namespace app;

		/** @class Router
			@brief Роутинг URL запросов
		*/
		class Router implements \app\interfaces\IRouter
		{
			private $request = null;

			/** Инициализация
				@param $r запрос
				@return объект Router
			*/
			public function __construct(\app\Request $r)
			{
				$this->request = $r;
			}

			/** Роутинг запроса
				@return URL карту или false
			*/
			public function route()
			{
				$request_uri = $this->request->getURI();

				foreach(\app\UrlMap::getMap() as $k => $v){
					if(preg_match("/^".addcslashes($k,'/')."/i", $request_uri)){
						$match = new \stdClass;
						$match->controller = (isset($v[0]) ? $v[0] : null);
						$match->action     = (isset($v[1]) ? $v[1] : null);
						
						$this->request->excludeMapKey($k);

						if(isset($v[2]))
							$match->options = $v[2];

						return $match;
					}
				}

				return false;
			}
		}
?>