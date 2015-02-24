<?php
	namespace core\sql;

	class Model_DatabaseFactory
	{
		protected static $engine_mysql    = array();
		protected static $engine_sqlite3  = array();
		protected static $engine_postgres = array();
		protected static $engine_cubrid   = array();

		private function __construct(){} 

		public static function getDriver($type = false,$name = false)
		{			
			if($type === false) $type = \app\conf\DBConnector::DEFAULT_ENGINE;
			if($name === false) $name = \app\conf\DBConnector::DEFAULT_NAME;

			$conf = \app\conf\DBConnector::getInstance()->get($type,$name);

			switch($type) {
				case 'mysql'    : return self::driverMYSQL      ($name, $conf);
				case 'sqlite'   : return self::driverSQLite3    ($name, $conf);
				case 'postgres' : return self::driverPostgreSQL ($name, $conf);
				case 'cubrid'   : return self::driverCUBRID     ($name, $conf);
				
				default:
					throw new \core\sql\DBException("Unknown or disabled driver {$type}");
			}
		}

		private static function driverMYSQL($name, $conf)
		{
			if(false === isset(self::$engine_mysql[$name])){
				self::$engine_mysql[$name] = new \core\sql\drivers\Model_MySql($conf);
				self::$engine_mysql[$name]->selectDatabase($conf['database']);
			}

			return self::$engine_mysql[$name];
		}

		private static function driverSQLite3($name, $conf)
		{
			if(false === isset(self::$engine_sqlite3[$name])){
				self::$engine_sqlite3[$name] = new \core\sql\drivers\Model_Sqlite3($conf);
				self::$engine_sqlite3[$name]->selectDatabase($conf['database']);
			}

			return self::$engine_sqlite3[$name];
		}
		
		private static function driverPostgreSql($name, $conf)
		{
			if(false === isset(self::$engine_postgres[$name])){
				self::$engine_postgres[$name] = new \core\sql\drivers\Model_PostgreSQL($conf);
				self::$engine_postgres[$name]->selectDatabase($conf['database']);
			}

			return self::$engine_postgres[$name];
		}

		private static function driverCUBRID($name, $conf)
		{
			if(false === isset(self::$engine_cubrid[$name])){
				self::$engine_cubrid[$name] = new \core\sql\drivers\Model_CUBRID($conf);
				self::$engine_cubrid[$name]->selectDatabase($conf['database']);
			}

			return self::$engine_cubrid[$name];
		}
	}
?>