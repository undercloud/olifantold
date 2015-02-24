<?php
	namespace domain\extproperty;
	use \core\sql\Model_ORM;

	class Model_ExtpropertyObject extends \domain\Model_Domain
	{
		public static function init()
		{
			return new self();
		}
		
		public function getObjects()
		{
			return Model_ORM::take('extproperty_object')
				->select()
				->where()
				->exec()
				->fetchAll('id');
		}
	}
?>
