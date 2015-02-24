<?php	
	class Controller_Base
	{
		public function __construct(){}
		public function __destruct(){}

		protected static function getUrlKey($class = false, $method = false)
		{
			$result = array();
			foreach(\app\UrlMap::getMap() as $url => $map){
				if(
					($class and $method)
					and
					(
						strtolower($class) == strtolower($map[0]) 
						and 
						strtolower($method) == strtolower($map[1]) 
					)
				)
					$result[$url] = $map;
				else if($class and !$method and strtolower($class) == strtolower($map[0]))
					$result[$url] = $map;
			}

			return $result;
		}
	}
?>