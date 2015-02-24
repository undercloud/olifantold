<?php
	class Model_EShopCatalog
	{
		private $eshop_catalog_table   = null;
		private $database_driver = null;

		public function __construct()
		{
			$this->eshop_catalog_table = Settings::ESHOP_CATALOG;
			$this->database_driver = Model_DataBaseDriver::getDriver('mysqli');
		}

		private function buildTree($data,$pid = 0)
		{
		    $formatted = array();
		    foreach($data as $item){
		        if($item['parent'] == $pid) {
		            $formatted[$item['id']] = $item;
		            $children =  $this->buildTree($data, $item['id'] );
		            if($children) {
		                $formatted[$item['id']]['children'] = $children;
		            }
		        }
		    }
		    return $formatted;
		}

		private function extractIds($data,$pid)
		{
			$ids = array();
			foreach($data as $item){
				if($item['parent'] == $pid){
					$ids[] = $item['id'];
					$ids = array_merge($ids,$this->extractIds($data,$item['id']));
				}
			}
			return $ids;
		}

		public function getCatalogTree($parent = false)
		{
			$fetched = $this->getCatalog(); 	
			return $this->buildTree($fetched,$parent);	
		}

		public function getCatalog($parent = false)
		{
			$sql_params = array("tables" => array($this->eshop_catalog_table));

			if($parent !== false){
				$parent = $this->database_driver->escapeString($parent);
				$sql_params['conditions'][] = array("parent","=",$parent);
			}

			$this->database_driver->execSql(
				Model_QuerySQL::findAll(
					$sql_params
				)
			);

			return $this->database_driver->allToAssoc(); 
		}

		public function getChildIds($parent)
		{
			$fetched = $this->getCatalog(); 
			return $this->extractIds($fetched,$parent);
		}

		public function countProducts($target = false)
		{
			$this->database_driver->execSql(
				Model_QuerySQL::findAll(
					array(
						"tables" => array('eshop_product'),
						"fields" => array("category","COUNT(*) AS count"),
						"group" => array("category")
					)
				)
			);

			$fetched = $this->database_driver->allToAssoc();
			$formatted = array();
			foreach($fetched as $key=>$item){
				$formatted[$item['category']] = $item['count'];
				unset($fetched[$k]);
			}

			return $formatted;
		}

		public function addItem($data)
		{
			foreach($data as $k=>$v)
				$data[$k] = $this->database_driver->escapeString($v);
			
			$this->database_driver->execSql(
				Model_QuerySQL::insertIn(
					array(
						"table" => $this->eshop_catalog_table,
						"data" => $data
					)
				)
			);
		}

		public function updateItem($id,$data)
		{
			$id = $this->database_driver->escapeString($id);

			foreach($data as $k=>$v)
				$data[$k] = $this->database_driver->escapeString($v);

			$this->database_driver->execSql(
				Model_QuerySQL::updateAll(
					array(
						"table" => $this->eshop_catalog_table,
						"data" => $data,
						"limit" => 1 
					)
				)
			);
		}

		public function removeItem($id)
		{
			$id = $this->database_driver->escapeString($id);

			$this->database_driver->execSql(
				Model_QuerySQL::deleteOut(
					array(
						"tables" => array($this->eshop_catalog_table),
						"conditions" => array(
							array("id","=",$id)
						),
						"limit" => 1
					)
				)
			);
		}
	}
?>