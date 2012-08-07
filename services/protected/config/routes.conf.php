<?php
/**
 * Define your URI routes here.
 *
 * $route[Request Method][Uri ] = array( Controller class, action method, other options, etc. )
 *
 * RESTful api support, *=any request method, GET PUT POST DELETE
 * POST 	Create
 * GET      Read
 * PUT      Update, Create
 * DELETE 	Delete
 */
$route['*']['/'] = array('ErrorController', 'index');
$route['*']['/error'] = array('ErrorController', 'index');

$mB = array(
    'clientKey' => '/^TL27|DAY23|NOC21$/',
    'format' => '/^json|xml$/',
    'city' => '/^[0-9]+$/',
    'service' => '/^[0-9]+$/',
    'category' => '/^[a-z]{2,}$/i',
    'start' => '/^(\\d{5,}|\\d{4}-\\d{2}-\\d{2}\\.\\d{4})$/',
    'end' => '/^(\\d{5,}|\\d{4}-\\d{2}-\\d{2}\\.\\d{4})$/',
    'channel' => '/^[0-9]+$/',
    'programName' => '/^.{2,}$/',
    'programCategory' => '/^[a-z0]*$/i',
    'program' => '/^[0-9]+$/',
    'page' => '/^[0-9]{1,2}$/',
    'results' => '/^[0-9]{1,2}$/'
);

//$route['*']['/gen_model'] = array('MainController', 'gen_models', 'authName' => 'Model Generator', 'auth' =>array('admin' => '1234'), 'authFail' => 'Unauthorized!');

// Verificación de usuario
$route['*']['/session/init'] = array( 'IDSessionController', 'index' );
// Redirección de usuario según codigo
$route['*']['/session/code/:code'] = array( 'IDSessionController', 'code', 'match' => array( 'code' => '/^.{2,}$/' ) );

// Ciudades
$route['*']['/ciudades/:clientKey/:format'] = array('PPAController', 'ciudades', 'match' => array( 'clientKey' => $mB['clientKey'], 'format' => $mB['format'] ) );

// Categorias
$route['*']['/categorias/:clientKey/:format'] 						= array('PPAController', 'categorias', 'match' => array( 'clientKey' => $mB['clientKey'], 'format' => $mB['format'] ) );
$route['*']['/categorias/:clientKey/:format/:city/:service']		= array('PPAController', 'categorias', 'match' => array( 'clientKey' => $mB['clientKey'], 'format' => $mB['format'], 'city' => $mB['city'], 'service' => $mB['service'] ) );

// Tios de servicio
$route['*']['/servicios/:clientKey/:format/:city'] = array('PPAController', 'servicios', 'match' => array( 'clientKey' => $mB['clientKey'], 'format' => $mB['format'], 'city' => $mB['city'] ) );

// Canales
$route['*']['/canales/:clientKey/:format/:city/:service']				= array('PPAController', 'canales', 'match' => array( 'clientKey' => $mB['clientKey'], 'format' => $mB['format'], 'city' => $mB['city'], 'service' => $mB['service'] ) );
$route['*']['/canales/:clientKey/:format/:city/:service/:category']		= array('PPAController', 'canales', 'match' => array( 'clientKey' => $mB['clientKey'], 'format' => $mB['format'], 'city' => $mB['city'], 'service' => $mB['service'], 'category' => $mB['category'] ) );

// Grilla General
$route['*']['/grilla/:clientKey/:format/:city/:service']					= array('PPAController', 'grilla', 'match' => array( 'clientKey' => $mB['clientKey'], 'format' => $mB['format'], 'city' => $mB['city'], 'service' => $mB['service'] ) );
$route['*']['/grilla/:clientKey/:format/:city/:service/:start']				= array('PPAController', 'grilla', 'match' => array( 'clientKey' => $mB['clientKey'], 'format' => $mB['format'], 'city' => $mB['city'], 'service' => $mB['service'], 'start' => $mB['start'] ) );
$route['*']['/grilla/:clientKey/:format/:city/:service/:start/:end']		= array('PPAController', 'grilla', 'match' => array( 'clientKey' => $mB['clientKey'], 'format' => $mB['format'], 'city' => $mB['city'], 'service' => $mB['service'], 'start' => $mB['start'], 'end' => $mB['end'] ) );

// Grilla General Categorias
$route['*']['/grilla/:clientKey/:format/:city/:service/:category']					= array('PPAController', 'grilla', 'match' => array( 'clientKey' => $mB['clientKey'], 'format' => $mB['format'], 'city' => $mB['city'], 'service' => $mB['service'], 'category' => $mB['category'] ) );
$route['*']['/grilla/:clientKey/:format/:city/:service/:category/:start']			= array('PPAController', 'grilla', 'match' => array( 'clientKey' => $mB['clientKey'], 'format' => $mB['format'], 'city' => $mB['city'], 'service' => $mB['service'], 'category' => $mB['category'], 'start' => $mB['start'] ) );
$route['*']['/grilla/:clientKey/:format/:city/:service/:category/:start/:end']		= array('PPAController', 'grilla', 'match' => array( 'clientKey' => $mB['clientKey'], 'format' => $mB['format'], 'city' => $mB['city'], 'service' => $mB['service'], 'category' => $mB['category'], 'start' => $mB['start'], 'end' => $mB['end'] ) );
$route['*']['/grilla/categoria/:clientKey/:format/:city/:service/:category']					= $route['*']['/grilla/:clientKey/:format/:city/:service/:category'];
$route['*']['/grilla/categoria/:clientKey/:format/:city/:service/:category/:start']				= $route['*']['/grilla/:clientKey/:format/:city/:service/:category/:start'];
$route['*']['/grilla/categoria/:clientKey/:format/:city/:service/:category/:start/:end']		= $route['*']['/grilla/:clientKey/:format/:city/:service/:category/:start/:end'];

// Grilla Canal
$route['*']['/grilla/canal/:clientKey/:format/:city/:service/:channel']					= array('PPAController', 'grillaCanal', 'match' => array( 'clientKey' => $mB['clientKey'], 'format' => $mB['format'], 'city' => $mB['city'], 'service' => $mB['service'], 'channel' => $mB['channel'] ) );
$route['*']['/grilla/canal/:clientKey/:format/:city/:service/:channel/:start']			= array('PPAController', 'grillaCanal', 'match' => array( 'clientKey' => $mB['clientKey'], 'format' => $mB['format'], 'city' => $mB['city'], 'service' => $mB['service'], 'channel' => $mB['channel'], 'start' => $mB['start'] ) );
$route['*']['/grilla/canal/:clientKey/:format/:city/:service/:channel/:start/:end']		= array('PPAController', 'grillaCanal', 'match' => array( 'clientKey' => $mB['clientKey'], 'format' => $mB['format'], 'city' => $mB['city'], 'service' => $mB['service'], 'channel' => $mB['channel'], 'start' => $mB['start'], 'end' => $mB['end'] ) );

// Grilla Programa
$route['*']['/grilla/programa/:clientKey/:format/:city/:service/:programName']																= array('PPAController', 'grillaPrograma', 'match' => array( 'clientKey' => $mB['clientKey'], 'format' => $mB['format'], 'city' => $mB['city'], 'service' => $mB['service'], 'programName' => $mB['programName'] ) );
$route['*']['/grilla/programa/:clientKey/:format/:city/:service/:programName/:programCategory']												= array('PPAController', 'grillaPrograma', 'match' => array( 'clientKey' => $mB['clientKey'], 'format' => $mB['format'], 'city' => $mB['city'], 'service' => $mB['service'], 'programName' => $mB['programName'], 'programCategory' => $mB['programCategory'] ) );
$route['*']['/grilla/programa/:clientKey/:format/:city/:service/:programName/:programCategory/:channel']									= array('PPAController', 'grillaPrograma', 'match' => array( 'clientKey' => $mB['clientKey'], 'format' => $mB['format'], 'city' => $mB['city'], 'service' => $mB['service'], 'programName' => $mB['programName'], 'programCategory' => $mB['programCategory'], 'channel' => $mB['channel'] ) );
$route['*']['/grilla/programa/:clientKey/:format/:city/:service/:programName/:programCategory/:channel/:page']								= array('PPAController', 'grillaPrograma', 'match' => array( 'clientKey' => $mB['clientKey'], 'format' => $mB['format'], 'city' => $mB['city'], 'service' => $mB['service'], 'programName' => $mB['programName'], 'programCategory' => $mB['programCategory'], 'channel' => $mB['channel'], 'page' => $mB['page'] ) );
$route['*']['/grilla/programa/:clientKey/:format/:city/:service/:programName/:programCategory/:channel/:page/:results']						= array('PPAController', 'grillaPrograma', 'match' => array( 'clientKey' => $mB['clientKey'], 'format' => $mB['format'], 'city' => $mB['city'], 'service' => $mB['service'], 'programName' => $mB['programName'], 'programCategory' => $mB['programCategory'], 'channel' => $mB['channel'], 'page' => $mB['page'], 'results' => $mB['results'] ) );
$route['*']['/grilla/programa/:clientKey/:format/:city/:service/:programName/:programCategory/:channel/:page/:results/:start']				= array('PPAController', 'grillaPrograma', 'match' => array( 'clientKey' => $mB['clientKey'], 'format' => $mB['format'], 'city' => $mB['city'], 'service' => $mB['service'], 'programName' => $mB['programName'], 'programCategory' => $mB['programCategory'], 'channel' => $mB['channel'], 'page' => $mB['page'], 'results' => $mB['results'], 'start' => $mB['start'] ) );
$route['*']['/grilla/programa/:clientKey/:format/:city/:service/:programName/:programCategory/:channel/:page/:results/:start/:end']			= array('PPAController', 'grillaPrograma', 'match' => array( 'clientKey' => $mB['clientKey'], 'format' => $mB['format'], 'city' => $mB['city'], 'service' => $mB['service'], 'programName' => $mB['programName'], 'programCategory' => $mB['programCategory'], 'channel' => $mB['channel'], 'page' => $mB['page'], 'results' => $mB['results'], 'start' => $mB['start'], 'end' => $mB['end'] ) );

// Programa
$route['*']['/programa/:clientKey/:format/:program']			= array('PPAController', 'programa', 'match' => array( 'clientKey' => $mB['clientKey'], 'format' => $mB['format'], 'program' => $mB['program'] ) );
$route['*']['/programa/:clientKey/:format/:program/:start']		= array('PPAController', 'programa', 'match' => array( 'clientKey' => $mB['clientKey'], 'format' => $mB['format'], 'program' => $mB['program'], 'start' => $mB['start'] ) );

// Log
$route['*']['/log/access/:clientKey/:format']	= array('PPAController', 'access', 'match' => array( 'clientKey' => $mB['clientKey'], 'format' => $mB['format'] ) );


