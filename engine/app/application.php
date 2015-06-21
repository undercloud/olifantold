<?php
	namespace app;

	class Application implements \app\interfaces\IApplication
	{
		protected static $instance = null;
		private $uri = null;

		private function __construct()
		{
			$this->uri = $_SERVER['REQUEST_URI'];
		}

		public static function getInstance()
		{	
			if(null === self::$instance)
				self::$instance = new self();
			return self::$instance;
		}

		public function run()
		{
			if($this->uri === '/')
				$this->uri = 'index';

			if(0 === strpos($this->uri,'/?'))
				$this->uri = 'index' . substr($this->uri,1);

			$request = new Request($this->uri);
			$request->prepareGlobalVars();
			$request->reorderFiles();

			$response = new Response();

			$router = new Router($request); 
			$callable = $router->route();

			if($callable === false){
				$callable = new \stdClass;
				$callable->controller = 'Controller_Error';
				$callable->action     = 'notFound404';
			}

			$input  = $request->build();
			$output = $response->prepare(); 

			\app\RequestManager::before($input,$output,$callable);

			$echo = FrontController::getInstance()
			->setController($callable->controller)
			->setAction($callable->action)
			->setParams($input,$output)
			->exec();

			\app\RequestManager::after($input,$output,$callable);

			if(is_object($echo)){
				$response->send($echo);
			}
		}
	}
?>