<?php
	namespace app\interfaces;

	interface IFrontController
	{
		public function setController($controller);
		public function setAction($action);
		public function setParams($request,$response);
		public function exec();
	}
?>