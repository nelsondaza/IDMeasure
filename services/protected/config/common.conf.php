<?php
/*
 * Common configuration that can be used throughout the application
 * Access via Singleton, eg. Doo::conf()->BASE_PATH;
 */
error_reporting( 0 );
date_default_timezone_set('America/Bogota');
ini_set('default_charset', 'UTF-8');

/**
 * for benchmark purpose, call Doo::benchmark() for time used.
 */
//$config['START_TIME'] = microtime(true);


//framework use, must defined, user full absolute path and end with / eg. /var/www/project/
$config['SITE_PATH'] = dirname( dirname( dirname( __FILE__ ) ) ) . '/';
//$config['PROTECTED_FOLDER'] = 'protected/';
$config['BASE_PATH'] = dirname( dirname( dirname( __FILE__ ) ) ) . '/dooframework/';

//for production mode use 'prod'
$config['APP_MODE'] = ( strpos( $_SERVER['HTTP_USER_AGENT'], 'DeveloperMode' ) !== false ? 'dev' : 'prod' );
$config['APP_MODE'] = 'dev';

if( $config['APP_MODE'] == 'dev' )  {
	error_reporting( E_ALL | E_STRICT );
    ini_set( "display_errors", 0 );
}
else    {
    error_reporting( 0 );
    ini_set( "display_errors", 0 );
}

//----------------- optional, if not defined, default settings are optimized for production mode ----------------
/*
$config['SUBFOLDER'] = str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace('\\','/',$config['SITE_PATH']));
if(strpos($config['SUBFOLDER'], '/')!==0){
	$config['SUBFOLDER'] = '/'.$config['SUBFOLDER'];
}
*/
$config['SUBFOLDER'] = '/services/';

$config['APP_URL'] = 'http://'.$_SERVER['HTTP_HOST'] . "/" ;
$config['AUTOROUTE'] = false;
$config['DEBUG_ENABLED'] = ( $config['APP_MODE'] == 'dev' );
$config['AUTOLOAD'] = array( 'controller', 'model', 'class' );

//$config['TEMPLATE_COMPILE_ALWAYS'] = TRUE;

//register functions to be used with your template files
//$config['TEMPLATE_GLOBAL_TAGS'] = array('url', 'url2', 'time', 'isset', 'empty');

/**
 * defined either Document or Route to be loaded/executed when requested page is not found
 * A 404 route must be one of the routes defined in routes.conf.php (if autoroute on, make sure the controller and method exist)
 * Error document must be more than 512 bytes as IE sees it as a normal 404 sent if < 512b
 */
//$config['ERROR_404_DOCUMENT'] = 'error.php';
$config['ERROR_404_ROUTE'] = '/error';

/**
 * you can include self defined config, retrieved via Doo::conf()->variable
 * Use lower case for you own settings for future Compability with DooPHP
 */
//$config['pagesize'] = 10;

