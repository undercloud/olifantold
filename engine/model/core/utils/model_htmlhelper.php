<?php
	namespace core\utils;

	class Model_HtmlHelper
	{
		/*
			array(
				array(
					"title" =>
					"value" => string || array()
					"attr"  => array()
				)
			),

		*/

		public static function select(array $data,array $attr = array())
		{
			$html = "<select>";
			foreach($data as $value){
				if(is_array($value['value'])){
					$html .= "<optgroup label=\"{$value['title']}\">";
					foreach($value['value'] as $opt)
						$html .= "<option value=\"{$opt['value']}\">{$opt['title']}</option>";
					$html .= "</optgroup>";
				}else{
					$html .= "<option value=\"{$value['value']}\">{$value['title']}</option>";
				}
			}
			$html .= "</select>";

			return $html;
		}
	}
?>