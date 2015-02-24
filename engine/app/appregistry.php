<?php
	namespace app;

		/** @class AppRegistry
			@brief Класс - контейнер приложения
			@details Глобальное хранилище, извлечение и вставка данных 
			доступно из любой точки
		*/
		class AppRegistry implements \app\interfaces\IRegistry
		{
			/// @param $instance объект AppRegistry
			protected static $instance = null;
			private $storage           = null;
			private $storage_path      = null;
			private $modify            = false;

			private function __construct()
			{
				$this->storage_path = ENGINE_PATH . '/app/com/storage.reg';
				$this->storage = unserialize(file_get_contents($this->storage_path));
			}

			/** Экземпляр контейнера
				@return объект AppRegistry
			*/
			public static function getInstance()
			{	
				if(null === self::$instance)
					self::$instance = new self();
				return self::$instance;
			}

			/** Проверка присутствия ключа
				@param $key ключ
				@return true если ключ присутствует иначе false
			*/
			public function contains($key)
			{
				return isset($this->storage[$key]);
			}

			/** Получение хранимого свойства
				@param $key ключ
				@return начение ключа или выбрасывание исключения AppException
			*/
			public function get($key)
			{
				if(isset($this->storage[$key]))
					return $this->storage[$key];
				else
					throw new \app\exceptions\AppException('Undefined key: ' . $key);
			}

			/** Получение всего контейнера
				@return array контейнер
			*/
			public function getAll()
			{
				return $this->storage;
			}

			/** Установка значения в контейнере
				@param $key ключ
				@param $value значение
				@return null
			*/
			public function set($key,$value)
			{
				$this->storage[$key] = $value;
				$this->modify = true;
			}

			/** Удаление значения
				@param $key ключ
				@return null
			*/
			public function remove($key)
			{	
				unset($this->storage[$key]);
				$this->modify = true;
			}

			public function __destruct()
			{
				if(false === $this->modify)
					return;

				if(is_writable($this->storage_path))
					file_put_contents($this->storage_path, serialize($this->storage));
				else
					throw new \app\exceptions\AppException("Can't save registry");
			}
		}
?>