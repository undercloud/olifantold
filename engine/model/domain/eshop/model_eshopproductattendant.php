<?php
	class Model_EShopProductAttendant
	{
		private $eshop_product_attendant_table = null;
		private $eshop_product_table = null;
		private $database_driver = null;

		public function __construct()
		{
			$this->eshop_product_attendant_table = Settings::ESHOP_PRODUCT_ATTENDANT;
			$this->eshop_product_table = Settings::ESOP_PRODUCT;
			$this->database_driver = Model_DataBaseDriver::driverMYSQLImprove();
		}

		public function getAttendant($product_id)
		{
			$product_id = $this->database_driver->escapeString($product_id);

			$this->database_driver->execSql(
				Model_QuerySql::findAll(
					array(
						"tables" => array(
							$this->eshop_product_attendant_table,
							$this->eshop_product_table
						),
						"conditions" => array(
							array(
								$this->eshop_product_table.".id",
								"=",
								$this->eshop_product_attendant_table.".attendant_product_id",	
							),
							array("product_id","=",$product_id)
						)
					)
				)
			);

			$fetched = $this->database_driver->allToAssoc();
			
			$ids = array();
			foreach($fetched as $item)
				$ids[] = $item['attendant_product_id'];

			return array(
				"data" => $fetched,
				"ids" => $ids
			);
		}

		public function addItem($data)
		{
			foreach($data as $k=>$v)
				$data[$k] => $this->database_driver->escapeString($v);

			$this->database_driver->execSql(
				Model_QuerySql::insertIn(
					array(
						"table" => $this->eshop_product_attendant_table,
						"data" => $data
					)
				)
			);
		}

		public function delItem($product_id,$attendant_id)
		{
			$this->database_driver->execSql(
				Model_QuerySql::deleteOut(
					array(
						"tables" => array($this->eshop_product_attendant_table),
						"conditions" => array(
							array("product_id","=",$product_id),
							array("attendant_product_id","=",$attendant_id)
						),
						"limit" => 1

					)
				)
			);
		} 
	}
?>