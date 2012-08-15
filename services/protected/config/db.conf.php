<?php
/**
 * Example Database connection settings and DB relationship mapping
 * $dbmap[Table A]['has_one'][Table B] = array('foreign_key'=> Table B's column that links to Table A );
 * $dbmap[Table B]['belongs_to'][Table A] = array('foreign_key'=> Table A's column where Table B links to );
 */


//Clientes/Ciudades
$dbmap['User']['has_many']['UserProperties'] = array('foreign_key'=>'id_user');

$dbmap['UserProperties']['belongs_to']['User'] = array('foreign_key'=>'id_user');

/*
//Canales
$dbmap['Channel']['has_many']['ClientChannel'] = array('foreign_key'=>'channel');

//Programa
$dbmap['Channel']['has_many']['Slot'] = array('foreign_key'=>'channel');

//Canales.Cliente
$dbmap['ClientChannel']['belongs_to']['Client'] = array('foreign_key'=>'id');
$dbmap['ClientChannel']['belongs_to']['Channel'] = array('foreign_key'=>'id');

//Programas
$dbmap['Slot']['belongs_to']['Channel'] = array('foreign_key'=>'id');

//Cap�tulos
$dbmap['Chapter']['has_many']['SlotChapter'] = array('foreign_key'=>'chapter');

//Cap�tulo.Programas
$dbmap['SlotChapter']['belongs_to']['Chapter'] = array('foreign_key'=>'id');

//$dbconfig[ Environment or connection name] = array(Host, Database, User, Password, DB Driver, Make Persistent Connection?);
//for setting collation and charset 'collate'=>'utf8_unicode_ci', 'charset'=>'utf8'
//$dbconfig['dev'] = array('localhost', 'mydb', 'root', '1234', 'mysql', true, 'collate'=>'utf8_unicode_ci', 'charset'=>'utf8');

// app.conf.php

*/