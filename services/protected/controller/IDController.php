<?php

class IDController extends DooController {
	const DEFAULT_TIME_RANGE = 7200; // Segundos ( EJ: 3600 * 2 = Horas)
	const DEFAULT_DAYS_RANGE = 7; // Días
	const DEFAULT_TIME_CACHE = 80000; // Segundos ( 3600 * 24 )
	const FILTER_TIME_RANGE = 25200; // Horas
	
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
		$data = array_merge( array( 'list' => array( ), 'error' => null ), $data );
		
		parent::renderc( $this->params['format'], $data, $enableControllerAccess, $includeTagClass );
		
	}
	
	public function beforeRun( $resource, $action )	{
		
		IDController::$resource = $resource;
		IDController::$action = $action;
		IDController::$start = microtime( true );
		IDController::$newparams = $this->params;

		/*
		if( $this->userparams['clientKey'] != 'TL27' )	{
			$data['error'] = array( 'cod' => '401', 'desc' => 'Cliente no autorizado.' );
			$this->renderc( $data );
			exit( );
		}

		IDController::$URI = $_SERVER['REQUEST_URI'];
		if( strpos( $_SERVER['REQUEST_URI'], '?' ) !== false )
			$_SERVER['REQUEST_URI'] = substr( $_SERVER['REQUEST_URI'], 0, strpos( $_SERVER['REQUEST_URI'], '?' ) );
		$_SERVER['REQUEST_URI'] = str_replace( '/' . $this->params['clientKey'] . '/', '/', $_SERVER['REQUEST_URI'] );
		
		if( Doo::conf( )->get( 'APP_MODE' ) != 'dev' )	{
			if( $this->params['format'] == 'json' )	{
				if( isset( $_REQUEST['callback'] ) && $_REQUEST['callback'] )
					header( "Content-type: application/javascript; charset=utf-8" );
				else
					header( "Content-type: application/json; charset=utf-8" );
			}
			else if ( $this->params['format'] == 'xml' )
				header( "Content-type: text/xml; charset=utf-8" );

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

		}
		else
			var_dump( $_SERVER['REQUEST_URI'] );

		if( isset( $_REQUEST['callback'] ) && $_REQUEST['callback'] )	{
			echo $_REQUEST['callback'] . '(';
			register_shutdown_function ( "IDController::shutdown_function" );
		}
		else
			register_shutdown_function ( "IDController::shutdown_log_function" );
		
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
        IDController::shutdown_log_function( );
    }

    public static function shutdown_log_function( )	{
        /**
        if( Doo::conf( )->get( 'APP_MODE' ) != 'dev' )	{
        Doo::loadModel( 'MobileLog' );
        $log = new MobileLog( );
        $log->resource = IDController::$resource;
        $log->action = IDController::$action;
        $log->client = IDController::$newparams['clientKey'];
        $log->format = IDController::$newparams['format'];
        $log->date = date( 'Y-m-d H:i:s' );
        $log->agent = $_SERVER['HTTP_USER_AGENT'];
        $log->ip = $_SERVER['REMOTE_ADDR'];
        $log->duration = microtime( true ) - IDController::$start;
        $log->URI = IDController::$URI;
        $log->insert( );
        }
         **/
    }

    public function ciudades( )	{
		
		$clients = Doo::db()->find( 'Client', array( 'where' => "name LIKE 'TELMEX%' AND city IS NOT NULL", 'groupby' => 'city', 'order' => 'city' ) );
		$data['list'] = array( );
		
		/* @var $client Client */
		foreach( $clients as $client )
			$data['list'][] = array( 'id' => $client->id, 'nom' => $client->city );
		
		$this->renderc( $data );
	}
	
	public function categorias( )	{
		
		$filter = array(
				'match' => 'true',
				'where' => ( isset( $this->params['service'] ) ? 'client_channel.client = ? ' : "client.name LIKE 'TELMEX%'" ),
				'groupby' => 'client_channel._group',
				'order' => 'client_channel._group',
				'param' => ( isset( $this->params['service'] ) ? array( $this->params['service'] ) : null )
		);
		
		$categories = Doo::db( )->relate( 'ClientChannel', 'Client', $filter );
		$data['list'] = array( );
		
		/* @var $clientChannel ClientChannel */
		foreach( $categories as $clientChannel )
			if( $clientChannel->_group )
				$data['list'][] = array( 'id' => $clientChannel->_group );
		
		$this->renderc( $data );
	}
	
	public function servicios( )	{
		
		Doo::loadModel( 'Client' );
		$client = new Client( );
		$client->id = $this->params['city'];
		$client = Doo::db( )->find( $client, array( 'limit' => 1 ) );
		
		if( !$client )	{
			$data['error'] = array( 'cod' => '404', 'desc' => 'Ciudad no encontrada.' );
			$this->renderc( $data );
			return;
		}
			
		$clients = Doo::db()->find( 'Client', array( 'where' => "city = ?", 'groupby' => 'name', 'order' => 'name', 'param' => array( $client->city ) ) );
		$data['list'] = array( );
		
		/* @var $client Client */
		foreach( $clients as $client )	{
			$name = ucfirst( trim( preg_replace( '/TELMEX[\\W]*' . $client->city . '/i' , '', $client->name ) ) );
			$data['list'][] = array( 'id' => $client->id, 'nom' => $name );
		}
	
		$this->renderc( $data );
	}
	
	public function canales( )	{
		
		$filter = array(
				'match' => 'true',
				'where' => 'client_channel.client = ? ',
				'order' => 'client_channel.number',
				'param' => array( $this->params['service'] ),
		);
				
		if( isset( $this->params['category'] ) && $this->params['category'] )	{
			$filter['where'] .= ' AND client_channel._group LIKE ?';
			$filter['param'][] = urldecode( $this->params['category'] );
		}
	
		$channels = Doo::db( )->relate( 'Channel', 'ClientChannel', $filter );
		
		if( !$channels )	{
			$data['error'] = array( 'cod' => '404', 'desc' => 'Tipo de servicio o categoría no encontrados.' );
			$this->renderc( $data );
			return;
		}
		
		$data['list'] = array( );
		
		$order_channels = array( );
		/* @var $channel Channel */
		foreach( $channels as $channel )	{
			
			/* @var $clientChannel ClientChannel */
			foreach ( $channel->ClientChannel as $clientChannel )
				$order_channels[$clientChannel->number] = array( 'id' => $channel->id, 'nom' => $channel->name, 'num' => $clientChannel->number, 'logo' => self::PPA_PATH . ( $channel->logo == "" ? self::DEFAULT_CHANNEL_LOGO : $channel->logo ) );
			
		}
		
		ksort ( $order_channels );
		
		foreach( $order_channels as $channel )
			$data['list'][] = $channel;

		$this->renderc( $data );
		
	}
	
	public function canalesOLD( )	{
		
		$filter = array(
				'match' => 'true',
				'where' => 'client_channel.client = ? ',
				'order' => 'client_channel.number',
				'param' => array( $this->params['service'] ),
		);
				
		if( isset( $this->params['category'] ) && $this->params['category'] )	{
			$filter['where'] .= ' AND client_channel._group LIKE ?';
			$filter['param'][] = urldecode( $this->params['category'] );
		}
	
		$channels = Doo::db( )->relate( 'Channel', 'ClientChannel', $filter );
		
		if( !$channels )	{
			$data['error'] = array( 'cod' => '404', 'desc' => 'Tipo de servicio o categoría no encontrados.' );
			$this->renderc( $data );
			return;
		}
		
		$data['list'] = array( );
	
		/* @var $channel Channel */
		foreach( $channels as $channel )	{
			
			$numbers = "";
			/* @var $clientChannel ClientChannel */
			foreach ( $channel->ClientChannel as $clientChannel ){
				$numbers .= $clientChannel->number . ",";
			}
			$data['list'][] = array( 'id' => $channel->id, 'nom' => $channel->name, 'num' => trim( $numbers, "," ) , 'logo' => self::PPA_PATH . ( $channel->logo == "" ? self::DEFAULT_CHANNEL_LOGO : $channel->logo ) );
		}
		$this->renderc( $data );
		
	}
	
	/**
	 * Grilla de todos los canales en un rango de fecha y hora iniciales
	 */
	public function grilla( )	{
		
		$startTime = ( isset( $this->params['start'] ) ? ( is_numeric( $this->params['start'] ) ? (int)$this->params['start'] : strtotime( $this->params['start'] ) ) : time( ) );
		$endTime = ( isset( $this->params['end'] ) ? ( is_numeric( $this->params['end'] ) ? (int)$this->params['end'] : strtotime( $this->params['end'] ) ) : $startTime + self::DEFAULT_TIME_RANGE );
		
		$filter = array(
				'match' => 'true',
				'where' => 'client_channel.client = ? ',
				'order' => 'channel.name',
				'param' => array( $this->params['service'] ),
		);
				
		if( isset( $this->params['category'] ) && $this->params['category'] )	{
			$filter['where'] .= ' AND client_channel._group LIKE ?';
			$filter['param'][] = $this->params['category'];
		}
		
		$channels = Doo::db( )->relate( 'Channel', 'ClientChannel', $filter );
		
		if( !$channels )	{
			$data['error'] = array( 'cod' => '404', 'desc' => 'Tipo de servicio o categoría no encontrados.' );
			$this->renderc( $data );
			return;
		}
		
		$data['list'] = array( );
		
		/* @var $channel Channel */
		foreach( $channels as $channel )	{
			
			$numbers = "";
			/* @var $clientChannel ClientChannel */
			foreach ( $channel->ClientChannel as $clientChannel ){
				$numbers .= $clientChannel->number . ",";
			}
			
			$dataSlot = array(
					'id' => $channel->id,
					'nom' => $channel->name,
					'num' => trim( $numbers, "," ) ,
					'logo' => self::PPA_PATH . ( $channel->logo == "" ? self::DEFAULT_CHANNEL_LOGO : $channel->logo ),
					'cat' => ( !empty( $channel->ClientChannel ) ? $channel->ClientChannel[0]->_group : '' ),
					'prog' => array( )
			);
			
			$sql = "
				SELECT * FROM (
					SELECT slot.*, TIMESTAMP( CONCAT( slot.date, ' ', slot.time ) ) AS start_time, ADDDATE( CONCAT( slot.date, ' ', slot.time ), INTERVAL slot.duration MINUTE ) AS end_time
					FROM slot
					WHERE slot.channel = ?
					AND slot.date >= ?
					AND slot.date <= ?
				) as filter_slot
				WHERE
				( start_time BETWEEN ? AND ? OR end_time BETWEEN ? AND ? )
				OR
				( start_time <= ? AND end_time >= ? )
			";
			
			$slots = Doo::db( )->fetchAll( $sql, array(
					$channel->id,
					
					date( 'Y-m-d', $startTime - self::FILTER_TIME_RANGE ),
					date( 'Y-m-d', $endTime + self::FILTER_TIME_RANGE ),
					
					date( 'Y-m-d H:i:s', $startTime ),
					date( 'Y-m-d H:i:s', $endTime - 60 ), // Inician incluso un minuto antes de la hora final
					
					date( 'Y-m-d H:i:s', $startTime + 60 ), // NO terminan justo en ese minuto
					date( 'Y-m-d H:i:s', $endTime ),
					
					date( 'Y-m-d H:i:s', $startTime ), // Inician incluso un minuto antes
					date( 'Y-m-d H:i:s', $endTime ), // NO terminan justo en ese minuto
				)
			);
			
			if( $slots )	{
				foreach( $slots as $slot )	{
						$dataSlot['prog'][] = array(
						'id' => $slot['id'],
						'nom' => $slot['title'],
						'ini' => strtotime( $slot['start_time'] ),
						'fin' => strtotime( $slot['end_time'] )
					);
				}
			}
						
			$data['list'][] = $dataSlot;
			unset( $dataSlot );
		}
		
		$this->renderc( $data );
	}
	
	/**
	 * Grilla de un canal dado en un rango de fecha y hora iniciales
	 */
	public function grillaCanal( )	{
	
		$startTime = ( isset( $this->params['start'] ) ? ( is_numeric( $this->params['start'] ) ? (int)$this->params['start'] : strtotime( $this->params['start'] ) ) : time( ) );
		$endTime = ( isset( $this->params['end'] ) ? ( is_numeric( $this->params['end'] ) ? (int)$this->params['end'] : strtotime( $this->params['end'] ) ) : $startTime + self::DEFAULT_TIME_RANGE );
		
		$filter = array(
				'match' => 'true',
				'where' => 'client_channel.client = ? AND channel.id = ? ',
				'param' => array( $this->params['service'], $this->params['channel'] )
		);
		
		$data['list'] = array( );
		
		$channels = Doo::db( )->relate( 'Channel', 'ClientChannel', $filter );
		
		if( !$channels )	{
			$data['error'] = array( 'cod' => '404', 'desc' => 'Tipo de servicio o categoría no encontrados.' );
			$this->renderc( $data );
			return;
		}
		
		/* @var $channel Channel */
		foreach( $channels as $channel )	{
			$numbers = "";
			/* @var $clientChannel ClientChannel */
			foreach ( $channel->ClientChannel as $clientChannel ){
				$numbers .= $clientChannel->number . ",";
			}
			
			$dataSlot = array(
					'id' => $channel->id,
					'nom' => $channel->name,
					'num' => trim( $numbers, "," ) ,
					'logo' => self::PPA_PATH . ( $channel->logo == "" ? self::DEFAULT_CHANNEL_LOGO : $channel->logo ),
					'cat' => ( !empty( $channel->ClientChannel ) ? $channel->ClientChannel[0]->_group : '' ),
					'prog' => array( )
			);
			
			$sql = "
				SELECT * FROM (
					SELECT slot.*, TIMESTAMP( CONCAT( slot.date, ' ', slot.time ) ) AS start_time, ADDDATE( CONCAT( slot.date, ' ', slot.time ), INTERVAL slot.duration MINUTE ) AS end_time
					FROM slot
					WHERE slot.channel = ?
					AND slot.date >= ?
					AND slot.date <= ?
				) as filter_slot
				WHERE
				( start_time BETWEEN ? AND ? OR end_time BETWEEN ? AND ? )
				OR
				( start_time <= ? AND end_time >= ? )
			";
			
			$slots = Doo::db( )->fetchAll( $sql, array(
					$channel->id,
					
					date( 'Y-m-d', $startTime - self::FILTER_TIME_RANGE ),
					date( 'Y-m-d', $endTime + self::FILTER_TIME_RANGE ),
					
					date( 'Y-m-d H:i:s', $startTime ),
					date( 'Y-m-d H:i:s', $endTime - 60 ), // Inician incluso un minuto antes de la hora final
					
					date( 'Y-m-d H:i:s', $startTime + 60 ), // NO terminan justo en ese minuto
					date( 'Y-m-d H:i:s', $endTime ),
					
					date( 'Y-m-d H:i:s', $startTime ), // Inician incluso un minuto antes
					date( 'Y-m-d H:i:s', $endTime ), // NO terminan justo en ese minuto
				)
			);
			
			if( $slots )	{
				foreach( $slots as $slot )	{
						$dataSlot['prog'][] = array(
						'id' => $slot['id'],
						'nom' => $slot['title'],
						'ini' => strtotime( $slot['start_time'] ),
						'fin' => strtotime( $slot['end_time'] )
					);
				}
			}
						
			$data['list'][] = $dataSlot;
		}
		
		$this->renderc( $data );
	}
	
	/**
	 * Grilla de un programa dado en un rango de fecha y hora iniciales
	 */
	public function grillaPrograma( )	{
	
		$startTime = ( isset( $this->params['start'] ) ? ( is_numeric( $this->params['start'] ) ? (int)$this->params['start'] : strtotime( $this->params['start'] ) ) : time( ) );
		$endTime = ( isset( $this->params['end'] ) ? ( is_numeric( $this->params['end'] ) ? (int)$this->params['end'] : strtotime( $this->params['end'] ) ) : $startTime + ( self::DEFAULT_DAYS_RANGE * 24 * 60 * 60 ) );
		
		$page = ( isset( $this->params['page'] ) ? ( (int)$this->params['page'] > 0 ? (int)$this->params['page'] : 0 ) : 0 );
		$results = ( isset( $this->params['results'] ) ? ( (int)$this->params['results'] > 0 ? (int)$this->params['results'] : 5 ) : 5 );
		
		if( !isset( $this->params['programCategory'] ) )
			$this->params['programCategory'] = 0;
		if( !isset( $this->params['channel'] ) )
			$this->params['channel'] = 0;
		
		$sql = "
			SELECT * FROM (
				SELECT slot.*, TIMESTAMP( CONCAT( slot.date, ' ', slot.time ) ) AS start_time, ADDDATE( CONCAT( slot.date, ' ', slot.time ), INTERVAL slot.duration MINUTE ) AS end_time, MATCH( title ) AGAINST ( ? ) AS relevant
				FROM slot
				WHERE slot.date >= ?
				AND slot.date <= ?
				AND MATCH( title ) AGAINST ( ? )
				AND slot.channel IN (
					SELECT DISTINCT channel
					FROM client_channel
					WHERE client = ?
					AND _group " . ( $this->params['programCategory'] != "0" ? "LIKE" : "<>" ) . " ?
					AND channel " . ( $this->params['channel'] != "0" ? "=" : "<>" ) . " ?
				)
				HAVING relevant > 0.5
				ORDER BY relevant, title, channel, date, time
			) AS filter_slot
			WHERE
			( start_time BETWEEN ? AND ? OR end_time BETWEEN ? AND ? )
			OR
			( start_time <= ? AND end_time >= ? )
			GROUP BY title, channel
			ORDER BY start_time, relevant, title
		";
	
		$sqlData = array(
			$this->params['programName'],
			
			date( 'Y-m-d', $startTime - self::FILTER_TIME_RANGE ),
			date( 'Y-m-d', $endTime + self::FILTER_TIME_RANGE ),
			
			$this->params['programName'],
			$this->params['service'],
			$this->params['programCategory'],
			$this->params['channel'],
			
			date( 'Y-m-d H:i:s', $startTime ),
			date( 'Y-m-d H:i:s', $endTime - 60 ), // Inician incluso un minuto antes de la hora final
			
			date( 'Y-m-d H:i:s', $startTime + 60 ), // NO terminan justo en ese minuto
			date( 'Y-m-d H:i:s', $endTime ),
			
			date( 'Y-m-d H:i:s', $startTime ), // Inician incluso un minuto antes
			date( 'Y-m-d H:i:s', $endTime ) // NO terminan justo en ese minuto
		);
		
		$total = Doo::db( )->fetchRow( "SELECT COUNT(*) AS total FROM ( " . $sql . " ) AS TS", $sqlData );
		$total = $total['total'];
		
		if( $total == 0 )	{
			$sql = "
				SELECT * FROM (
					SELECT slot.*, TIMESTAMP( CONCAT( slot.date, ' ', slot.time ) ) AS start_time, ADDDATE( CONCAT( slot.date, ' ', slot.time ), INTERVAL slot.duration MINUTE ) AS end_time
					FROM slot
					WHERE slot.date >= ?
					AND slot.date <= ?
					AND slot.title LIKE ?
					AND slot.channel IN (
						SELECT DISTINCT channel
						FROM client_channel
						WHERE client = ?
						AND _group " . ( $this->params['programCategory'] != "0" ? "LIKE" : "<>" ) . " ?
						AND channel " . ( $this->params['channel'] != "0" ? "=" : "<>" ) . " ?
					)
					ORDER BY title, channel, date, time
				) AS filter_slot
				WHERE
				( start_time BETWEEN ? AND ? OR end_time BETWEEN ? AND ? )
				OR
				( start_time <= ? AND end_time >= ? )
				GROUP BY title, channel
				ORDER BY start_time, title
			";
			
			$sqlData = array(
				date( 'Y-m-d', $startTime - self::FILTER_TIME_RANGE ),
				date( 'Y-m-d', $endTime + self::FILTER_TIME_RANGE ),
				
				"%" . $this->params['programName'] . "%",
				$this->params['service'],
				$this->params['programCategory'],
				$this->params['channel'],
				
				date( 'Y-m-d H:i:s', $startTime ),
				date( 'Y-m-d H:i:s', $endTime - 60 ), // Inician incluso un minuto antes de la hora final
				
				date( 'Y-m-d H:i:s', $startTime + 60 ), // NO terminan justo en ese minuto
				date( 'Y-m-d H:i:s', $endTime ),
				
				date( 'Y-m-d H:i:s', $startTime ), // Inician incluso un minuto antes
				date( 'Y-m-d H:i:s', $endTime ) // NO terminan justo en ese minuto
			);
			
			$total = Doo::db( )->fetchRow( "SELECT COUNT(*) AS total FROM ( " . $sql . " ) AS TS", $sqlData );
			$total = $total['total'];
		}
		
		$data['list'] = array( );
		$data['pag'] = $page;
		$data['pags'] = ceil( (int)$total / $results );
		
		
		if( $total > 0 && $page > ceil( (int)$total / $results ) - 1 )	{
			$data['error'] = array( 'cod' => '400', 'desc' => 'La página solicitada no existe.' );
			$this->renderc( $data );
			return;
		}
		
		if( $page < 0 )	{
			$data['error'] = array( 'cod' => '400', 'desc' => 'La página solicitada no existe.' );
			$this->renderc( $data );
			return;
		}
				
		$slots = Doo::db( )->fetchAll( $sql . " LIMIT " . $results . " OFFSET " . ( $page * $results ), $sqlData );
		if( $slots )	{
			$channels = array( );
			foreach( $slots as $slot )	{
				if( !isset( $channels[$slot['channel']] ) )
					$channels[$slot['channel']] = array( );
				$channels[$slot['channel']][] = $slot;
			}
			
			foreach( $channels as $id_channel => $slots )	{
			
				$filter = array(
						'match' => 'true',
						'where' => 'client_channel.client = ? AND channel.id = ? ',
						'param' => array( $this->params['service'], $id_channel )
				);
		
				$channel = Doo::db( )->relate( 'Channel', 'ClientChannel', $filter );
				
				if( $channel && count( $channel ) > 0 )	{
					$channel = $channel[0];
					
					$numbers = "";
					/* @var $clientChannel ClientChannel */
					foreach ( $channel->ClientChannel as $clientChannel ){
						$numbers .= $clientChannel->number . ",";
					}
			
					$dataSlot = array(
							'id' => $channel->id,
							'nom' => $channel->name,
							'num' => trim( $numbers, "," ) ,
							'logo' => self::PPA_PATH . ( $channel->logo == "" ? self::DEFAULT_CHANNEL_LOGO : $channel->logo ),
							'cat' => ( !empty( $channel->ClientChannel ) ? $channel->ClientChannel[0]->_group : '' ),
							'prog' => array( )
					);
				
					foreach( $slots as $slot )	{
							$dataSlot['prog'][] = array(
							'id' => $slot['id'],
							'nom' => $slot['title'],
							'ini' => strtotime( $slot['start_time'] ),
							'fin' => strtotime( $slot['end_time'] )
						);
					}
					
					$data['list'][] = $dataSlot;
				}
			}
		}
		
		$this->renderc( $data );
	}
	
	/**
	 * Detalle de programa
	 */
	public function programa( )	{
		
		$startTime = ( isset( $this->params['start'] ) ? ( is_numeric( $this->params['start'] ) ? (int)$this->params['start'] : strtotime( $this->params['start'] ) ) : time( ) );
		$startTime = mktime( 0, 0, 0, date( "m", $startTime ), date( "d", $startTime ), date( "Y", $startTime ) );
		$endTime = mktime( 0, 0, 0, date( "m", $startTime ), date( "d", $startTime ) + self::DEFAULT_DAYS_RANGE, date( "Y", $startTime ) );
			
		Doo::loadModel( 'Slot' );
		$slot = new Slot( );
		$slot->id = $this->params['program'];
		$slot = $slot->find( array( 'limit' => 1 ) );
		
		if( !$slot )	{
			$data['error'] = array( 'cod' => '404', 'desc' => 'Programa no encontrado.' );
			$this->renderc( $data );
			return;
		}
		
		$filter = array(
				'match' => 'true',
				'where' => 'slot_chapter.slot = ? ',
				'param' => array( $slot->id ),
				'limit' => 1
		);
		
		$data['list'] = array( );
		
		$chapter = Doo::db( )->relate( 'Chapter', 'SlotChapter', $filter );
		
		/**
		 * Parece que no es un error, solo no tiene sinopsis.
		**/
		if( !$chapter )	{
			$image = self::DEFAULT_CHANNEL_LOGO;
			$chapter = new Chapter( );
			//$data['error'] = array( 'cod' => '500', 'desc' => 'Error interno al buscar capítulo.' );
			//$this->renderc( $data );
			//return;
		}
		else
			$image = 'chapter_images/70x70/' . $chapter->id . ".jpg";
		
		if( !file_exists( self::PPA_DIR . $image ) )	{
			Doo::loadModel( 'Channel' );
			$channel = new Channel( );
			$channel->id = $slot->channel;
			$channel = $channel->find( array( 'limit' => 1 ) );
			
			if( !$channel )	{
				$data['error'] = array( 'cod' => '500', 'desc' => 'Error interno al buscar canal.' );
				$this->renderc( $data );
				return;
			}
			
			$image = $channel->logo;
			if( !$image )
				$image = self::DEFAULT_CHANNEL_LOGO;
		}
		
		
		$dataSlot = array(
				'id'   => $slot->id,
				'nom'  => $slot->title,
				'logo' => self::PPA_PATH . $image,
				'desc' => trim( str_replace( "\n", "\\n", str_replace( "\r", "", $chapter->description ) ) ),
				'gene' => '',
				'clas' => '',
				'ano'  => '',
				'dire' => '',
				'acto' => '',
				'prog' => array( )
		);
		
		
		if( $chapter->movie )	{
			Doo::loadModel( 'Movie' );
			$movie = new Movie( );
			$movie->id = $chapter->movie;
			$movie = $movie->find( array( 'limit' => 1 ) );
			
			if( !$movie )	{
				$data['error'] = array( 'cod' => '500', 'desc' => 'Error interno al buscar película.' );
				$this->renderc( $data );
				return;
			}
			
			$dataSlot['gene'] = $movie->gender;
			$dataSlot['clas'] = $movie->rated;
			$dataSlot['ano']  = $movie->year;
			$dataSlot['dire'] = $movie->director;
			$dataSlot['acto'] = $movie->actors;
		}
		else if( $chapter->serie )	{
			Doo::loadModel( 'Serie' );
			$serie = new Serie( );
			$serie->id = $chapter->serie;
			$serie = $serie->find( array( 'limit' => 1 ) );
			
			if( !$serie )	{
				$data['error'] = array( 'cod' => '500', 'desc' => 'Error interno al buscar serie.' );
				$this->renderc( $data );
				return;
			}
			
			$dataSlot['gene'] = $serie->gender;
			$dataSlot['clas'] = $serie->rated;
			$dataSlot['ano']  = $serie->year;
			$dataSlot['acto'] = $serie->starring;
		}
		else if( $chapter->special )	{
			Doo::loadModel( 'Special' );
			$special = new Special( );
			$special->id = $chapter->special;
			$special = $special->find( array( 'limit' => 1 ) );
			
			if( !$special )	{
				$data['error'] = array( 'cod' => '500', 'desc' => 'Error interno al buscar especial.' );
				$this->renderc( $data );
				return;
			}
			
			$dataSlot['gene'] = $special->gender;
			$dataSlot['clas'] = $special->rated;
			$dataSlot['ano']  = ( isset( $special->year ) ? $special->year : '' );
			$dataSlot['acto'] = $special->starring;
		}
		
		$sql = "
			SELECT slot.*, TIMESTAMP( CONCAT( slot.date, ' ', slot.time ) ) AS start_time, ADDDATE( CONCAT( slot.date, ' ', slot.time ), INTERVAL slot.duration MINUTE ) AS end_time
			FROM slot
			WHERE slot.channel = ?
			AND slot.date >= ?
			AND slot.date <= ?
			AND slot.title = ?
		";
		
		$slots = Doo::db( )->fetchAll( $sql, array(
				$slot->channel,
				date( 'Y-m-d', $startTime ),
				date( 'Y-m-d', $endTime ),
				$slot->title
			)
		);
		
		if( $slots )	{
			foreach( $slots as $slot )	{
				$dataSlot['prog'][] = array(
						'id' => $slot['id'],
						'nom' => $slot['title'],
						'ini' => strtotime( $slot['start_time'] ),
						'fin' => strtotime( $slot['end_time'] )
				);
			}
		}
		
		$data['list'][] = $dataSlot;
		$this->renderc( $data );
	}


	public function access( )	{
	
		$logs = Doo::db()->find( 'MobileLog', array( 'where' => "client = 'TL27'", 'limit' => "20", 'desc' => 'id' ) );
		$data['list'] = array( );
	
		/* @var $log MobileLog */
		foreach( $logs as $log )
			$data['list'][] = array( 'id' => $log->id, 'dat' => $log->date, 'pro' => $log->duration, 'age' => $log->agent, 'uri' => $log->URI );
	
		$this->renderc( $data );
	}
	
}
