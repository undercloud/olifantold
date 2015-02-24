<?php
	namespace app;
		/** @class UrlMap
			@brief URL Mapper
			@details Содержит связку URL / Controller / Action
		*/
		final class UrlMap
		{
			private static $map = array();

			private function __construct(){}
			
			/** Установка карты URL
				@return null
			*/
			public static function bind(array $map)
			{
				self::$map = $map;
			}

			/** Получение карты URL
				@return карта URL
			*/
			public static function getMap()
			{
				return self::$map;
			}
		}
?>