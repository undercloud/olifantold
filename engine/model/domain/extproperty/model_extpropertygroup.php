<?php
	namespace domain\extproperty;
	use \core\sql\Model_ORM;

	class Model_ExtpropertyGroup extends \domain\Model_Domain
	{
		public static function init()
		{
			return new self();
		}

		public function bind($group_id,$property_id)
		{
			Model_ORM::take('extproperty_group_childs')
			->insert(array(
				'group_id'    => $group_id,
				'property_id' => $property_id
			))
			->exec();
		}
	}
?>