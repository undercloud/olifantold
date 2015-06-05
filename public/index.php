<?php
	//check version
	/*
	if(defined('PHP_VERSION_ID') == false){
		$version = explode('.', PHP_VERSION);
		define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
	}

	define('PHP_VERSION_REQUIRE', 50300);
	if(PHP_VERSION_ID < PHP_VERSION_REQUIRE)
		throw new Exception("PHP version requre " . PHP_VERSION_REQUIRE . " or higher");
	*/
	
	
	require_once __DIR__ . '/../engine/bootstrap.php';
	\app\Application::getInstance()->run();

	var_dump($_SERVER,$_REQUEST);
?>