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
	const BACKTRACE_ERRORS = true;
	const BACKTRACE_EXCEPTIONS = true;

	date_default_timezone_set(TIME_ZONE);
	set_time_limit(TIME_LIMIT);

	//access
	ini_set('open_basedir', DOCUMENT_ROOT . '/../');

	//session
	ini_set('session.gc_divisor', 100);
	ini_set('session.gc_maxlifetime', 40);
	ini_set('session.gc_probability', 100);

	//new \core\utils\Model_SessionHandler();
	//session_set_cookie_params(1623600);
	//session_start();
	//$_SESSION['sasai'] = 'lalka';
?>