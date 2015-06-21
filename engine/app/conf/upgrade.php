<?php
	if(!function_exists('array_column')){
		function array_column($array, $column_key, $index_key = null){
			return array_reduce($array, function ($result, $item) use ($column_key, $index_key) {
				if(null === $index_key){
					$result[] = $item[$column_key];
				}else{
					$result[$item[$index_key]] = $item[$column_key];
				}

				return $result;
			},array());
		}
	}
?>