<?php
	namespace app;
		/** @class Autoload
			@brief Загрузчик классов
			@details Выполняет автоматический поиск и включение класса
		*/
		class Autoload
		{
			/** Метод загрузки класса 
				@param $name подключаемый класс
				@return подключает класс или выбраcывает AppException
			*/
			public static function load($name)
			{
				$name_low = mb_strtolower(NAMESPACE_SEPARATOR != DIRECTORY_SEPARATOR  ? str_replace(NAMESPACE_SEPARATOR, DIRECTORY_SEPARATOR, $name) : $name);

				$paths = array(
					"/",
					"/controller/",
					"/model/",
					"/extension/"
				);				

				foreach($paths as $path){
					$fullpath = ENGINE_PATH . $path . $name_low . '.php';
					if(is_file($fullpath) == true){
						require_once $fullpath;
						return;
					}
				}
			}
		}
?>