<?php
	namespace app\interfaces;
	/** class Registry
		@brief Глобальное хранилище сущностей
	*/
	interface IRegistry
	{
		/** Получение хранимого свойства
			@param $key ключ
			@return начение ключа или выбрасывание исключения AppException
		*/
		public function get($key);

		/** Проверка присутствия ключа
			@param $key ключ
			@return true если ключ присутствует иначе false
		*/
		public function contains($key);

		/** Установка значения в контейнере
			@param $key ключ
			@param $value значение
			@return null
		*/
		public function set($key,$value);

		/** Удаление значения
			@param $key ключ
			@return null
		*/
		public function remove($key);
	}
?>