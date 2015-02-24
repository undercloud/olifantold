<?php
	class Model_EShopBrands
	{
		private $eshop_brands_table = null;
		private $database_driver    = null;

		public function __construct()
		{
			$this->eshop_brands_table = Settings::ESHOP_BRANDS;
			$this->database_driver = Model_DataBaseDriver::driverMYSQLImprove();
		}

		public function getBrandsForType($type)
		{

		}

		public function getBrandsByIds($ids)
		{

		}

		public function addItem($data)
		{
			foreach($data as $k=>$v)
				$data[$k] = $this->database_driver->escapeString($v);

			$this->database_driver->execSql(
				Model_QuerySql::insertIn(
					array(
						"table" => $this->eshop_brands_table,
						"data" => $data
					)
				)
			);

			return $this->database_driver->lastInsertId();
		}

		public function updateItem($id,$data)
		{
			$id = $this->database_driver->escapeString($id);

			foreach($data as $k=>$v)
				$data[$k] = $this->database_driver->escapeString($v);

			$this->database_driver->execSql(
				Model_QuerySql::updateAll(
					array(
						"table" => $this->eshop_brands_table,
						"conditions" => array(
							array("id","=",$id)
						),
						"data" => $data,
						"limit" => 1
					)
				)
			);
		}

		public function deleteItem($id)
		{
			$id = $this->database_driver->escapeString($id);

			$this->database_driver->execSql(
				Model_QuerySql::deleteOut(
					array(
						"tables" => array($this->eshop_brands_table),
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