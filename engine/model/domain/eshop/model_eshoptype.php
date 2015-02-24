<?php
	class Model_EShopType
	{
		private $eshop_type_table = null;
		private $database_driver = null;

		public function __construct()
		{
			$this->eshop_type_table = Settings::ESHOP_TYPE;
			$this->database_driver  = Model_DataBaseDriver::driverMYSQLImprove();
		}

		public function getTypes($ids = false)
		{
			$query_params = array(
				"tables" => array($this->eshop_type_table)
			);

			if($ids !== false){
				foreach ($ids as $k=>$v){
					$ids[$k] = $this->database_driver->escapeString($v);
				}

				$query_params['conditions'][] = array("id","IN","(" . implode(",",$ids) . ")");
			}


			$this->database_driver->execSql(
				Model_QuerySql::findAll(
					$query_params
				)
			);

			return $this->database_driver->allToAssoc();
		}

		public function addItem($data)
		{
			foreach($data as $k=>$v)
				$data[$k] = $this->database_driver->escapeString($v);

			$this->database_driver->execSql(
				Model_QuerySql::insertIn(
					array(
						"table" => $this->eshop_type_table,
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
						"table" => $this->eshop_type_table,
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
						"tables" => array($this->eshop_type_table),
						"conditions" => array(
							array("id","=",$id)
						),
						"limit" =>1
					)
				)
			);
		}
	}
?>