<?php
	namespace app;

	class FrontController implements \app\interfaces\IFrontController
	{
		protected static $instance = null;

		protected $controller = null;
		protected $action     = null;
		protected $request    = null;
		protected $response   = null;

		public static function getInstance()
		{
			if(null === self::$instance)
				self::$instance = new self();

			return self::$instance;
		}

		public function setController($controller)
		{
			$this->controller = $controller;
			return $this;
		}

		public function setAction($action)
		{
			$this->action = $action;
			return $this;
		}

		public function setParams($request,$response)
		{
			$this->request  = $request;
			$this->response = $response;

			return $this;
		}

		public function exec()
		{
			if(DEV_MODE == DEBUG){
				if(null === $this->controller)
					throw new \app\exceptions\AppException('Class controller is not defined');

				if(false === class_exists($this->controller))
					throw new \app\exceptions\AppException('Class ' . $this->controller . ' not found');

				if(false === is_subclass_of($this->controller,'Controller_Base'))
					throw new \app\exceptions\AppException('Class ' . $this->controller . ' is not instanceof Controller_Base');

				if(null === $this->action)
					throw new \app\exceptions\AppException('Method ' . $this->action . ' is not defined');

				if(false === method_exists($this->controller, $this->action))
					throw new \app\exceptions\AppException('Method ' . $this->action .' not found in controller ' . $this->controller);
			}

			return call_user_func_array(
				array(
					new $this->controller(), 
					$this->action
				), 
				array(
					$this->request,
					$this->response
				)
			);
		}
	}
?>