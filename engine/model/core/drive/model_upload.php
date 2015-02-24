<?php
	namespace core\drive;

		class Model_Upload
		{
			public function accept($ext)
			{

			}

			public static function move($fn)
			{
				if(is_callable($fn) === false)
					throw new \AppException("Invalid callback");
					

				foreach($_FILES as $file){
					if (is_uploaded_file($file['tmp_name'])){

					}
				}
			}
		}
?>