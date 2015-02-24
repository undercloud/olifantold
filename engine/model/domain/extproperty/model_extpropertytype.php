<?php
	namespace domain\extproperty;
	//use \core\sql\Model_ORM;

	class Model_ExtpropertyType extends \domain\Model_Domain
	{
		private $types = array(
			'boolean',
			'select',
			'multiselect',
			'date',
			'daterange',
			'datetime',
			'datetimerange',
			'time',
			'timerange',
			'integer',
			'integerrange',
			'decimal',
			'decimalrange',
			'text'
		); 

		public function getTypes()
		{
			return $this->types;
		}
	}
?>