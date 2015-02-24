<?php
	namespace app\conf;

	class DBConnector
	{
		private function __construct(){}

		private static $instance = null;
		private static $connections = array();

		const DEFAULT_ENGINE = "mysql";
		const DEFAULT_NAME   = "default";

		public static function getInstance()
		{
			if(null === self::$instance)
				self::$instance = new self();
			
			return self::$instance;
		}

		public function push(array $conf)
		{
			foreach($conf as $type => $name)
				foreach($name as $key => $value)
					self::$connections[$type][$key] = $value;
		}

		public function get($type = false,$name = false)
		{
			if(isset(self::$connections[$type],self::$connections[$type][$name]))
				return self::$connections[$type][$name];

			throw new \app\exceptions\AppException("connection {$type}::{$name} not found");
		}
	}
?>