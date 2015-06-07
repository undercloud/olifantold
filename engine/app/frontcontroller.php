<?php
	namespace app;
		/** @class FrontController
			@brief Контроллер приложения
			@details Создание класса контроллера и вызов метода с параметрами запроса
		*/
		class FrontController implements \app\interfaces\IFrontController
		{
			/// @param $instance Объект FrontController
			protected static $instance = null;

			///@param $controller Класс контроллер
			protected $controller = null;
			///@param $action Вызываемый метод
			protected $action     = null;
			///@param $params Аргументы метода
			protected $params     = array();

			private function __construct(){}

			/** Экземпляр приложения
				@return объект FrontController
			*/
			public static function getInstance()
			{
				if(null === self::$instance)
					self::$instance = new self();

				return self::$instance;
			}

			/** Установка класса - контроллера
				@param $controller Класс контроллера
				@return объект FrontController
			*/

			public function setController($controller)
			{
				$this->controller = $controller;
				return $this;
			}

			/** Установка вызываемого метода
				@param $action вызываемый метод
				@return объект FrontController
			*/
			public function setAction($action)
			{
				$this->action = $action;
				return $this;
			}

			/** Установка аргументов
				@param $params аргументы
				@return объект FrontController
			*/
			public function setParams(array $params)
			{
				$this->params = $params;
				return $this;
			}

			/** Создание экземпляра класса контроллера и вызов метода
				@return Результат вызова метода контроллера
			*/
			public function exec()
			{
				if(DEV_MODE == DEBUG){
					if(null === $this->controller)
						throw new \app\exceptions\AppException("Controller class is not defined");

					if(!class_exists($this->controller))
						throw new \app\exceptions\AppException("class {$this->controller} not found");

					if(is_subclass_of($this->controller,'Controller_Base') === false)
						throw new \app\exceptions\AppException("class {$this->controller} fail instanceof Controller_Base");

					if(null === $this->action)
						throw new \app\exceptions\AppException("Method {$this->action} is not defined");

					if(!method_exists($this->controller, $this->action))
						throw new \app\exceptions\AppException("Method {$this->action} not found in {$this->controller}");
				}

				return call_user_func_array(array(new $this->controller(), $this->action), array($this->params));
			}
		}
?>