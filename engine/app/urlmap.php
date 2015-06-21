<?php
	namespace app;

	class UrlMap
	{
		private static $map = array();

		public static function bind(array $map)
		{
			self::$map = $map;
		}

		public static function getMap()
		{
			return self::$map;
		}
	}
?>