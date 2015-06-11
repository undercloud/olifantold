<?php
	\app\UrlMap::bind(
		array(
			"index"  => array("Controller_Index","index", array()),
			"nested/sub" => array("Controller_UnitTest","sub"),
			"nested" => array("Controller_UnitTest","nested"),
			"tester" => array("Controller_Index","tester"),
			"sql"    => array("Controller_Index","sql"),
			"audio"  => array("Controller_Index","ok"),
			"image"  => array("Controller_Index","image"),
			"imgs"   => array("Controller_Index","img"),
			"upload" => array("Controller_Index","upload"),
			"ext"    => array("Controller_Index","ext"),
			"auto"   => array("Controller_Index","auto"),
			"json"   => array("Controller_Index","json")
		)
	);
?>