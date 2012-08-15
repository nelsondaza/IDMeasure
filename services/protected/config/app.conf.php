<?php
//$dbconfig[ Environment or connection name] = array(Host, Database, User, Password, DB Driver, Make Persistent Connection?);
//for setting collation and charset 'collate'=>'utf8_unicode_ci', 'charset'=>'utf8'
//$dbconfig['dev'] = array('localhost', 'mydb', 'root', '1234', 'mysql', true, 'collate'=>'utf8_unicode_ci', 'charset'=>'utf8');
$config['FACEBOOK'] = array( );
$config['FACEBOOK']['CANVAS_PAGE'] = "http://idmeasure.onezink.com/";
$config['FACEBOOK']['APP_ID'] = '206103019518328';
$config['FACEBOOK']['APP_SECRET'] = '296903b8b4654449515dc5c259b90b69';


$dbconfig['dev']  = array( '127.0.0.1', 'cnwpccj_idmeasure', 'cnwpccj_measure', 'mes0487', 'mysql', true, 'collate'=>'latin1_swedish_ci', 'charset'=>'latin1' );
$dbconfig['prod'] = array( '127.0.0.1', 'cnwpccj_idmeasure', 'cnwpccj_measure', 'mes0487', 'mysql', true, 'collate'=>'latin1_swedish_ci', 'charset'=>'latin1' );

