<?php
	class Controller_UnitTest extends Controller_Base
	{
		public function nested($req)
		{
			echo $req->originalUrl;
		}

		public function sub($req)
		{
			echo $req->originalUrl .' [:sub]';
		}
	}
?>