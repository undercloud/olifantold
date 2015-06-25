<?php
	namespace app;

	class MiddleWare
	{
		public static function before(&$req,&$res,$callable){}
		public static function after(&$req,&$res,$callable){}
	}

?>