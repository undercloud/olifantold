<?php
	namespace core\utils;

	class Model_Array
	{
		public static function unsetValues(array $array, array $values)
		{
			return array_diff($array,$values);
		}
		
		public static function sortArrayByArray(array $array, array $orderArray){
			$ordered = array();
			foreach($orderArray as $key) {
				if(array_key_exists($key,$array)) {
					$ordered[$key] = $array[$key];
					unset($array[$key]);
				}
			}
			return $ordered + $array;
		}

		public static function extractField(array $array,$field)
		{
			$fields = array();
			foreach($array as $a)
				if(isset($a[$field]))
					$fields[] = $a[$field];

			return $fields;
		}
	}
?>