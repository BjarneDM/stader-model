<?php namespace Stader\Eksempler ;

   $include_paths[] = dirname( __DIR__ ) ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/cdn/php' ;
// $include_paths[] =  '.' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/cdn/_/php' ;
set_include_path( implode( ':' , $include_paths ) ) ;

// echo 'IncludePaths : ' . \PHP_EOL ;
// print_r( explode( ':' , get_include_path() ) ) ;

require_once( 'classloader.php' ) ;

use \Stader\Model\Tables\Ticket\{Ticket} ;

$thisTicket = new Ticket( 'header' , 'mangler batteri.' ) ;
// $thisTicket = new Ticket( 1 ) ;
print_r( $thisTicket->getData() ) ;
$thisTicket->setValues( [ 'header' => 'mangler 12v batteri.' ] ) ;
print_r( $thisTicket->getData() ) ;

?>
