<?php
	namespace core\data; 

		class Model_BreadCrumbs
		{
			private $storage = null;
			private $current = 0;
			private $id_field = null;
			private $parent_field = null;
			
			public function __construct($storage)
			{
				$this->storage = $storage;
			}
			
			public function setCurrent($current)
			{
				$this->current = $current;
			}
			
			public function setFields($id,$parent)
			{
				$this->id_field = $id;
				$this->parent_field = $parent;
			}
			
			public function build()
			{
				$count = count($this->storage);
				$builded = array();
				
				$next = $this->current;
				
				while($next != 0){
					$i = 0;
					foreach($this->storage as $s){
						if($s[$this->id_field] == $next){
							$builded[] = $s;
							$next = $s[$this->parent_field];
						}else{
							if($i + 1 == $count and $next == $this->current){
								throw new \app\exceptions\AppException("Can't find parent");
							}
						}
						$i++;
					}
				}
				return array_reverse($builded);
			}
		}
?>