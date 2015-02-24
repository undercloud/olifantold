<?php
	class Model_EShopProduct
	{
		private $eshop_product_table   = null;
		private $database_driver = null;
		
		public function __construct()
		{
			$this->eshop_product_table = Settings::ESHOP_PRODUCT;
			$this->database_driver = Model_DataBaseDriver::driverMYSQLImprove();
		}

		public function findProducts($options,$page,$limit)
		{
			$page  = $this->database_driver->escapeString($page); 
			$limit = $this->database_driver->escapeString($limit);

			$this->database_driver->execSql(
				Model_QuerySql::findAll(
					array(
						"SQL_CALC_FOUND_ROWS" => true,
						"tables" => array($this->eshop_product_table),
						"conditions"=> $options['conditions'],
						"order" => $options['order'],
						"limit" => $limit,
						"skip" => ($page - 1) * $limit 
					)
				)
			);

			$fetched = $this->database_driver->allToAssoc();

			$ids = array();
			foreach ($fetched as $k=>$v)
				$ids[] = $v['id'];

			$this->database_driver->execSql("SELECT FOUND_ROWS() as total");
			$total = $this->database_driver->allToAssoc();

			return array(
				"data" => $fetched,
				"total" => $total[0]['total'],
				"ids" => $ids
			);
		}

		public function addItem($data)
		{
			foreach($data as $k=>$v)
				$data[$k] = $this->database_driver->escapeString($v);

			$this->database_driver->execSql(
				Model_QuerySql::insertIn(
					array(
						"table" => $this->eshop_product_table,
						"data" => $data
					)
				)
			);

			return $this->database_driver->lastInsertId();
		}

		public function updateItem($id,$data)
		{
			$id = $this->database_driver->escapeString($id);

			foreach ($data as $k=>$v)
				$data[$k] = $this->database_driver->escapeString($v);

			$this->database_driver->execSql(
				Model_QuerySql::updateAll(
					array(
						"table" => $this->eshop_product_table,
						"conditions" => array(
							array("id","=",$id)
						),
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
				Model_QuerySql::deleteOut(
					array(
						"tables" => array($this->eshop_product_table),
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