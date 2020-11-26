<?php  namespace stader\control ;
header( 'Content-Type: application/json' ) ;

set_include_path( '/Volumes/Bjarne/Sites/info/mathiesen/zbc/stader/php' ) ;
require_once( dirname( __file__ , 2 ) . '/rpc/class.jsonrpc.php' ) ;
use \stader\rpc\{JsonRPC} ;

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

?>
