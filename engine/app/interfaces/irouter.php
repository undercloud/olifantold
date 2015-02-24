<?php
	namespace app\interfaces;

	/** interface IRouter
		@brief Роутинг URL запросов
	*/
	interface IRouter
	{
		/** Роутинг запроса*/
		public function route();
	}
?>