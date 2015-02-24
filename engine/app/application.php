<?php
	namespace app;
		/** @class Application
			@brief Класс приложения
			@details Предоставляет точку входа в приложение
		*/
		class Application implements \app\interfaces\IApplication
		{
			/// @param $instance объект Application
			protected static $instance = null;

			private function __construct(){}
			
			/** Экземпляр приложения
				@return объект Application
			*/
			public static function getInstance()
			{	
				if(null === self::$instance)
					self::$instance = new self();
				return self::$instance;
			}

			/** Инициализация приложения
				@return объект Application
			*/
			public function init($callback = null)
			{
				if(is_callable($callback))
					call_user_func($callback);
				return $this;
			}

			/** Запуск приложения
				@return null
			*/
			public function run()
			{
				if(!isset($_SERVER) or !isset($_SERVER['REQUEST_URI']) or empty($_SERVER['REQUEST_URI']))
					throw new \app\exceptions\AppException("Request URI is not defined");
					
				if($_SERVER['REQUEST_URI'] === "/")
					$_SERVER['REQUEST_URI'] = "index";

				$request = new Request($_SERVER['REQUEST_URI']);
				$request->prepareGlobalVars();
				$request->reorderFiles();

				$router = new Router($request); 
				$callable = $router->route();

				if($callable === false){
					$callable = new \stdClass;
					$callable->controller = 'Controller_Error';
					$callable->action     = 'notFound404';
				}else{
					$request->excludeMapKey($callable->mapkey);
				}

				if(isset($callable->options)){
					/* */
				}

				FrontController::getInstance()
				->setController($callable->controller)
				->setAction($callable->action)
				->setParams($request->parse())
				->run();
			}

			/** Запрет клонирования
				@return null
			*/
			public function __clone() 
			{
				throw new \app\exceptions\AppException("Cannot clone instance of Singleton pattern");
			}

			/** Запрет десериализации
				@return null
			*/
			public function __wakeup() 
			{
				throw new \app\exceptions\AppException('Cannot deserialize instance of Singleton pattern');
			}

			public function __destruct(){}
		}
?>