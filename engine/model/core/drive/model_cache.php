<?php	
	namespace core\drive;

		class Model_Cache
		{
			private $keys = null;
			private $cache_path = null;
			
			public function __construct()
			{
				$this->keys = array();
			}
			
			public function openStorage($path)
			{
				$this->cache_path = $path;
				
				$h = opendir($path);
				if(!$h)
					throw new \app\exceptions\AppException("Fail to open: " . $path);
				
				while(false !== ($f = readdir($h))){
					if($f != '.' and $f != '..')
						$this->keys[] = $f;
				}
			}
			
			public function clear()
			{
				foreach($this->keys as $k){
					unlink($this->cache_path.'/'.$k);
					unset($this->key[$k]);
				}
			}
			
			public function keyExists($key)
			{
				return in_array($key,$this->keys);
			}
			
			public function	size()
			{
				return count($this->keys);
			}
			
			public function insert($key, $object)
			{
				if(!is_writable($this->cache_path))
					throw new \app\exceptions\AppException("Fail to write: " . $this->cache_path.'/'.$key);

				file_put_contents($this->cache_path.'/'.$key,serialize($object));
				$this->keys[] = $key;
			}
			
			public function isEmpty()
			{
				return ($this->size() == 0) ? true : false;
			}
			
			public function getKeys()
			{
				return $this->keys;
			}
			
			public function getObject($key)
			{
				if(!$this->keyExists($key))
					return false;

				if(!is_readable($this->cache_path.'/'.$key))
					throw new \app\exceptions\AppException("Can't read: " . $this->cache_path.'/'.$key);

				return unserialize(file_get_contents($this->cache_path.'/'.$key));
			}
			
			public function createdAt($key)
			{
				return filectime($this->cache_path.'/'.$key);
			}

			public function remove($key)
			{
				if($this->keyExists($key)){
					unset($this->keys[$key]);
					unlink($this->cache_path.'/'.$key);
				}
			}
		}
?>