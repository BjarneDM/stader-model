<?php namespace stader\eksempler ;

   $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/stader/php' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/cdn/php' ;
// $include_paths[] =  '.' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/cdn/_/php' ;
set_include_path( implode( ':' , $include_paths ) ) ;

require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;

use \stader\model\{TicketStatus,TypeByte} ;


$allTypeBytes = new \stader\model\TypeBytes( $setup::$connect ) ;

do { print_r( $allTypeBytes->current()->getData() ) ;
} while ( $allTypeBytes->next() !== false ) ;

for ( $i = 0 ; $i < $allTypeBytes->count() ; $i++ ) 
    print_r( $allTypeBytes->getTypeByte( $i )->getData() ) ;

foreach ( $allTypeBytes->getTypeBytes() as $typebyte )
    print_r( $typebyte->getData() ) ;

print_r( $allTypeBytes ) ;

$allTicketStatuses = new \stader\model\TicketStatuses( $setup::$connect ) ;

do { print_r( $allTicketStatuses->current()->getData() ) ;
} while ( $allTicketStatuses->next() !== false ) ;

for ( $i = 0 ; $i < $allTicketStatuses->count() ; $i++ ) 
    print_r( $allTicketStatuses->getTicketStatus( $i )->getData() ) ;

foreach ( $allTicketStatuses->getTicketStatuses() as $ticketstatus )
    print_r( $ticketstatus->getData() ) ;

print_r( $allTicketStatuses ) ;

?>
