<?php
	namespace app\conf;

	define('DEBUG'   , 0x000);
	define('RELEASE' , 0xFFF);
	define('DEV_MODE', DEBUG); // DEBUG | RELEASE

	if(DEV_MODE === DEBUG){
		$error_reporting = E_ALL | E_STRICT;
		$error_mode = 1;
	}else if(DEV_MODE === RELEASE){
		$error_reporting = 0;
		$error_mode = 0;
	}

	error_reporting($error_reporting);
	ini_set("display_errors", $error_mode);
	ini_set("display_startup_errors",$error_mode);
	ini_set('xdebug.default_enable', $error_mode);
	ini_set("log_errors", $error_mode);

	const TIME_ZONE  = "UTC";
	const TIME_LIMIT = 30;

	date_default_timezone_set(TIME_ZONE);
	set_time_limit(TIME_LIMIT);

	//access
	ini_set('open_basedir', DOCUMENT_ROOT . '/../');

	//session
	ini_set('session.name', 'UNIQSESSID');
	ini_set('session.cookie_lifetime',3600);
	ini_set('session.cookie_httponly',1);
	ini_set('session.gc_divisor', 100);
	ini_set('session.gc_maxlifetime', 40);
	ini_set('session.gc_probability', 100);

	//new \core\utils\Model_SessionHandler();
	session_start();

	//$_SESSION['sasai'] = 'lalka';

	ini_set('highlight.comment','#969896');		 
	ini_set('highlight.default','#395063');	 
	ini_set('highlight.html'   ,'#888888');		 
	ini_set('highlight.keyword','#2d93c6');	 
	ini_set('highlight.string' ,'#05ad97');

	ini_set('expose_php','0');
?>