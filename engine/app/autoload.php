<?php
	namespace app;

	class Autoload
	{

		public static function load($name)
		{
			$name_low = mb_strtolower(NAMESPACE_SEPARATOR != DIRECTORY_SEPARATOR  ? str_replace(NAMESPACE_SEPARATOR, DIRECTORY_SEPARATOR, $name) : $name);

			$paths = array('/','/controller/','/model/');				

			foreach($paths as $path){
				$fullpath = ENGINE_PATH . $path . $name_low . '.php';

				if(file_exists($fullpath)){
					require_once $fullpath;
					return;
				}
			}
		}
	}
?>