<?php  namespace stader\control ;

set_include_path( '/Volumes/Bjarne/Sites/info/mathiesen/zbc/stader/php' ) ;

header( 'Content-Type: application/json' ) ;

require_once( dirname( __file__ , 2 ) . '/rpc/class.jsonrpc.php' ) ;
use \stader\rpc\{JsonRPC} ;

$input = [] ;

$input = ( $argc  ) ? array_slice( $argv, 1 ) : $input ;
$input = ( $_GET  ) ? array_keys( $_GET  )    : $input ;
$input = ( $_POST ) ? array_keys( $_POST )    : $input ;

$response = [] ;

foreach ( $input as $jsonData ) 
{
    $jsonRPC = new JsonRPC( $jsonData ) ;
    $response[] = $jsonRPC->getResponse() ;
}

echo implode( \PHP_EOL .  ',' . \PHP_EOL , $response ) ;

?>
