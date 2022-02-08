<?php  namespace Stader\Rpc ;
ob_start() ;
error_reporting( E_ERROR ) ;
header( 'Content-Type: application/json' ) ;

set_include_path( dirname( __DIR__ ) . '/php' ) ;
require_once( 'classloader.php' ) ;
use \Stader\Rpc\{JsonRPC} ;

$input = [] ;

$input = ( $argc  ) ? array_slice( $argv, 1 ) : $input ;
$input = ( $_GET  ) ? array_keys( $_GET  )    : $input ;
$input = ( $_POST ) ? array_keys( $_POST )    : $input ;

$response = [] ;

foreach ( $input as $jsonData ) 
{
    $response[] = ( new JsonRPC( $jsonData ) )->getResponse() ;
}

echo implode( \PHP_EOL .  ',' . \PHP_EOL , $response ) ;

ob_end_flush() ; ?>
