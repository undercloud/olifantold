<?php
	namespace app\interfaces;

	/** interface IApplication
		@brief Интерфейс приложения
	*/
	interface IApplication
	{
		/** Инициализация
			@return null
		*/
		public function init();
		
		/** Запуск
			@return null
		*/
		public function run();
	}
?>