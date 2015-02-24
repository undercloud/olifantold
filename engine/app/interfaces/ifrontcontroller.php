<?php
	namespace app\interfaces;

	/** interface IFrontController
		@brief Контроллер входа
	*/
	interface IFrontController
	{
		/** Установка класса - контроллера
			@param $controller имя создаваемого контроллера
		*/
		public function setController($controller);
		
		/** Установка вызываемого метода
			@param $action вызываемый метод
		*/
		public function setAction($action);
		
		/** Установка аргументов
			@param $params аргументы
		*/
		public function setParams(array $params);

		/** Создание экземпляра класса контроллера и вызов метода
		*/
		public function run();
	}
?>