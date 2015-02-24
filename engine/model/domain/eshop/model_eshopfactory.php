<?php
	class Model_EShopFactory
	{
		protected static $eshop_catalog = null;
		protected static $eshop_product = null;
		protected static $eshop_productattendant = null;
		//protected static $eshop_catalog = null;
		//protected static $eshop_catalog = null;

		public static function getObject($object)
		{
			switch($object){
				case 'Model_EShopCatalog':
					
				break;
			}
		}
	}
?>