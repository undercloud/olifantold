<?php
	namespace domain\extproperty;
    use \core\sql\Model_ORM;

	class Model_ExtpropertySet extends \domain\Model_Domain
	{
		public static function init()
		{
			return new self();
		}

		public function getSet($object_id,$object_set_id)
		{
			return Model_ORM::take('extproperty_set')
				->select()
				->where(array(
					'object_id' => $object_id,
					'object_set_id' => $object_set_id
				))
				->exec()
				->fetchAll();
		}

		public function bind($object_id,$object_set_id,$property_id)
		{
			Model_ORM::take('extproperty_set')
			->insert(array(
				'object_id'     => $object_id,
				'object_set_id' => $object_set_id,
				'property_id'   => $property_id
			))
			->exec();
		}
	}
?>