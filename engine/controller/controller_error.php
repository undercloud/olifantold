<?php
	class Controller_Error extends Controller_Base
	{
		public static function notFound404($r)
		{
			echo \app\view\Responser::sendHtml(
				\app\view\Document::getInstance()
				->load('404')
				->render()
			); 
		}
	}
?>