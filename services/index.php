<?php

include './protected/config/common.conf.php';
include './protected/config/db.conf.php';
include './protected/config/app.conf.php';
include './protected/config/routes.conf.php';

if( $config['APP_MODE'] == 'dev' )  {
    include $config['BASE_PATH'].'Doo.php';
    include $config['BASE_PATH'].'app/DooConfig.php';
    include $config['BASE_PATH'].'diagnostic/debug.php';
}
else
    include $config['BASE_PATH'].'deployment/deploy.php';

spl_autoload_register( 'Doo::autoload' );
Doo::conf( )->set( $config );


# database usage
//Doo::useDbReplicate();	#for db replication master-slave usage
Doo::db()->setMap( $dbmap );
Doo::db()->setDb( $dbconfig, $config['APP_MODE'] );
Doo::db()->sql_tracking = ( $config['APP_MODE'] == 'dev' );


Doo::app()->route = $route;

# Uncomment for DB profiling
# Doo::logger()->beginDbProfile('doowebsite');
Doo::app()->run();
# Doo::logger()->endDbProfile('doowebsite');
# Doo::logger()->rotateFile(20);
# Doo::logger()->writeDbProfiles();

