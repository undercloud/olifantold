<?php
	namespace app\interfaces;

	interface IRegistry
	{
		public function get($key);
		public function contains($key);
		public function set($key,$value);
		public function remove($key);
	}
?>