<?php
	namespace domain\extproperty;
	use \core\sql\Model_ORM;

	class Model_ExtpropertySelect extends \domain\Model_Domain
	{
		public static function init()
		{
			return new self();
		}
		
		public function addVariants(array $variants)
		{
			return Model_ORM::take('extproperty_select')
			->insert($variants,true)
			->exec()
			->lastInsertId();
		}
	}
?>