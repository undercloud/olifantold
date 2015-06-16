<?php
	namespace core\utils;

		class Model_CookieHelper
		{
			public static function getReader()
			{
				return new Model_CookieReader();
			}

			public static function getWriter()
			{
				return new Model_CookieWriter();
			}
		}

		class Model_CookieReader
		{
			public static function get($key = false){
				if(false === $key){
					return $_COOKIE;
				}else{
					return (isset($_COOKIE[$key]) ? $_COOKIE[$key]: null);
				}
			}

			public static function has($key){
				return isset($_COOKIE[$key]);
			}
		}

		class Model_CookieWriter
		{
			private static $queue = array();

			public static function set(array $c)
			{
				if(false == isset($c['name'])){
					throw new \app\exceptions\AppException('Invalid cookie params');
				}

				if(false == isset($c['value']))    $c['value']    = '';
				if(false == isset($c['expire']))   $c['expire']   = 0;
				if(false == isset($c['path']))     $c['path']     = '';
				if(false == isset($c['domain']))   $c['domain']   = '';
				if(false == isset($c['secure']))   $c['secure']   = false;
				if(false == isset($c['httponly'])) $c['httponly'] = false;

				self::$queue[] = $c;
			}

			public static function remove($name)
			{
				self::set(
					array(
						'name'   => $name,
						'value'  => null,
						'expire' => -1
					)
				);
			}

			public static function write()
			{
				foreach(self::$queue as $c){
					setcookie(
						$c['name'],
						$c['value'],
						$c['expire'],
						$c['path'],
						$c['domain'],
						$c['secure'],
						$c['httponly']
					);
				}
			}
		}
?>