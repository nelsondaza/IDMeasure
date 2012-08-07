<?php

class IDGlobalController extends DooController {

    protected static $session = null;

	public static $start = 0;
	public static $resource = '';
	public static $action = '';
	public static $newparams = array();
	public static $URI = '';
	
	/**
	 * @override solo en caso de poner excepciones
	 * @see DooController::render()
	 */
	public function renderc( $file, $data = null, $enableControllerAccess = false, $includeTagClass = true )	{
		
		if( !$data && is_array( $file ) )
			$data = $file;
		$data = array_merge( array( 'list' => array( ), 'error' => null, 'action' => null ), $data );

        if( !is_string( $file ) )   {
            $file = $this->params['format'];
        }

		parent::renderc( $file, $data, $enableControllerAccess, $includeTagClass );
		
	}
	
	public function beforeRun( $resource, $action )	{

        self::$session = Doo::session("IDMeasure");

		IDGlobalController::$resource = $resource;
		IDGlobalController::$action = $action;
		IDGlobalController::$start = microtime( true );
		IDGlobalController::$newparams = $this->params;

		/*
		if( $this->userparams['clientKey'] != 'TL27' )	{
			$data['error'] = array( 'cod' => '401', 'desc' => 'Cliente no autorizado.' );
			$this->renderc( $data );
			exit( );
		}
		*/

		IDGlobalController::$URI = $_SERVER['REQUEST_URI'];
        /*
		if( strpos( $_SERVER['REQUEST_URI'], '?' ) !== false )
			$_SERVER['REQUEST_URI'] = substr( $_SERVER['REQUEST_URI'], 0, strpos( $_SERVER['REQUEST_URI'], '?' ) );
		$_SERVER['REQUEST_URI'] = str_replace( '/' . $this->params['clientKey'] . '/', '/', $_SERVER['REQUEST_URI'] );
        */

        if( !isset( $this->params['format'] ) || !$this->params['format'] )
            $this->params['format'] = 'json';

        if( $this->params['format'] == 'json' )	{
            if( isset( $_REQUEST['callback'] ) && $_REQUEST['callback'] )
                header( "Content-type: application/javascript; charset=utf-8" );
            else
                header( "Content-type: application/json; charset=utf-8" );
        }
        else if ( $this->params['format'] == 'xml' )
            header( "Content-type: text/xml; charset=utf-8" );

        if( Doo::conf( )->get( 'APP_MODE' ) != 'dev' )	{

            /*
			$lastModified = time( );
			$uri = $_SERVER['REQUEST_URI'];

			if( $uri[strlen( $uri ) - 1] == '/' )	{
				$uri = substr( $uri, 0, strlen( $uri ) - 1 );
			}

			$cacheFile = Doo::conf()->SITE_PATH . Doo::conf()->PROTECTED_FOLDER . 'cache/frontend/' . str_replace( '/', '-', $uri ) . '.html';
			if( strncmp( PHP_OS, 'WIN', 3 ) === 0 )	{
				$cacheFile = str_replace( '?', '_q.', $cacheFile );
			}

			if ( file_exists( $cacheFile ) ) {
				$lastModified = filemtime( $cacheFile );
			}
			
			$ts = gmdate("D, d M Y H:i:s", $lastModified ) . " GMT";
			header("Last-Modified: $ts", true);
			$ts = gmdate("D, d M Y H:i:s", $lastModified + self::DEFAULT_TIME_CACHE ) . " GMT";
			header("Expires: $ts", true);
			header("Pragma: cache", true);
			//header_remove("Cache-Control");
			header("Cache-Control: max-age=" . self::DEFAULT_TIME_CACHE, true );
            */
		}

		if( isset( $_REQUEST['callback'] ) && $_REQUEST['callback'] )	{
			echo $_REQUEST['callback'] . '(';
			register_shutdown_function ( "IDGlobalController::shutdown_function" );
		}
		else
			register_shutdown_function ( "IDGlobalController::shutdown_log_function" );

        /*
		if( Doo::conf( )->get( 'APP_MODE' ) != 'dev' && $this->params['clientKey'] != 'NOC21' )	{
			Doo::cache('front')->get( self::DEFAULT_TIME_CACHE );
		}
		
		
		if( Doo::conf( )->get( 'APP_MODE' ) != 'dev' && $this->params['clientKey'] != 'NOC21' )
			Doo::cache('front')->start( );
		*/
	}

	public function afterRun( $routeResult )	{
		/*
		parent::afterRun( $routeResult );
		
		if( Doo::conf( )->get( 'APP_MODE' ) != 'dev' && $this->params['clientKey'] != 'NOC21' )
			Doo::cache('front')->end( );
		*/
	}

    public static function shutdown_function( )	{
        echo ')';
        IDGlobalController::shutdown_log_function( );
    }

    public static function shutdown_log_function( )	{
        /**
        if( Doo::conf( )->get( 'APP_MODE' ) != 'dev' )	{
        Doo::loadModel( 'MobileLog' );
        $log = new MobileLog( );
        $log->resource = IDGlobalController::$resource;
        $log->action = IDGlobalController::$action;
        $log->client = IDGlobalController::$newparams['clientKey'];
        $log->format = IDGlobalController::$newparams['format'];
        $log->date = date( 'Y-m-d H:i:s' );
        $log->agent = $_SERVER['HTTP_USER_AGENT'];
        $log->ip = $_SERVER['REMOTE_ADDR'];
        $log->duration = microtime( true ) - IDGlobalController::$start;
        $log->URI = IDGlobalController::$URI;
        $log->insert( );
        }
         **/
    }

}
