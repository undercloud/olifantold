<?php
	namespace domain\extproperty;
	use \core\sql\Model_ORM;

	class Model_Extproperty extends \domain\Model_Domain
	{
		//$id or array(1,2,3...)

		public function getPropertyById($id)
		{
			$ext = Model_ORM::take('extproperty')
				->select()
				->where(array(
						'id' => $id
					))
				->exec();

			if(is_array($id))
				return $ext->fetchAll('id');
			else
				return $ext->fetch();
		}

		public function addProperty($options)
		{
			$property_id = Model_ORM::take('extproperty')
			->insert(array(
				'name'     => $options['name'],
				'data_type' => $options['data_type'] 
			))
			->exec()
			->lastInsertId();

			Model_ExtpropertySet::init()->bind(
				$options['object_id'],
				$options['object_set_id'],
				$property_id
			);

			if(isset($options['group_id']))
				Model_ExtpropertyGroup::init()->bind(
					$options['group_id'],
					$property_id
				);


			if(isset($options['variants'])){
				foreach($options['variants'] as &$value)
					$value['property_id'] = $property_id;

				Model_ExtpropertySelect::init()->addVariants($options['variants']);
			}

			return $property_id;
		}
	}
?>