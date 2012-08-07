<?php

//register global/PHP functions to be used with your template files
//You can move this to common.conf.php   $config['TEMPLATE_GLOBAL_TAGS'] = array('isset', 'empty');
//Every public static methods in TemplateTag class (or tag classes from modules) are available in templates without the need to define in TEMPLATE_GLOBAL_TAGS 
Doo::conf()->TEMPLATE_GLOBAL_TAGS = array('toXML', 'toJSON', 'debug');

function debug($var){
    if(!empty($var)){
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }
}

function toXML( $data )	{
	foreach( $data as $node => $value )	{
		if( is_numeric( $node ) )
			$node = 'elem';
	
		echo '<' . $node . '>';
		if( is_array( $value ) || is_object( $value ) )
			toXML( $value );
		else
			echo str_replace("&", "&amp;", $value);
		
		echo '</' . $node . '>';
	}
}

function json_encode_string( $in_str )	{
	if( mb_check_encoding( $in_str ) != "UTF-8" )
		$in_str = mb_convert_encoding( $in_str, "UTF-8" );
	return str_replace( '"', '\\"', $in_str );
}

function toJSON( $arr )	{
	$json_str = "";
	if( is_array( $arr ) )	{
		$pure_array = true;
		$array_length = count( $arr );
		for( $i = 0; $i < $array_length; $i ++ )	{
			if( !isset( $arr[$i] ) )	{
				$pure_array = false;
				break;
			}
		}
		if( $pure_array )	{
			$json_str ="[";
			$temp = array();
			for( $i = 0; $i < $array_length; $i ++ )	{
				$temp[] = sprintf( "%s", toJSON( $arr[$i] ) );
			}
			$json_str .= implode(",",$temp);
			$json_str .="]";
		}
		else	{
			$json_str = "{";
			$temp = array();
			foreach( $arr as $key => $value )	{
				$temp[] = sprintf( "\"%s\":%s", $key, toJSON( $value ) );
			}
			$json_str .= implode( ",",$temp );
			$json_str .= "}";
		}
	}
	else	{
		if( is_string( $arr ))	{
			$json_str = "\"". json_encode_string( $arr ) . "\"";
		}
		else if( is_numeric( $arr ) )	{
			$json_str = $arr;
		}
		else if( is_null( $arr ) )	{
			$json_str = 'null';
		}
		else	{
			$json_str = "\"". json_encode_string($arr) . "\"";
		}
	}
	return $json_str;
}
