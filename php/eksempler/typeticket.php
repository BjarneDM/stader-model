<?php namespace Stader\Eksempler ;

   $include_paths[] = dirname( __DIR__ ) ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/cdn/php' ;
// $include_paths[] =  '.' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/cdn/_/php' ;
set_include_path( implode( ':' , $include_paths ) ) ;

require_once( 'classloader.php' ) ;

use \Stader\Model\Tables\TicketStatus\{TicketStatus,TicketStatuses} ;
use \Stader\Model\Tables\TypeByte\{TypeByte,TypeBytes} ;


$allTypeBytes = new TypeBytes() ;

do { print_r( $allTypeBytes->current()->getData() ) ;
} while ( $allTypeBytes->next() !== false ) ;

for ( $i = 0 ; $i < $allTypeBytes->count() ; $i++ ) 
    print_r( $allTypeBytes( $i )->getData() ) ;

foreach ( $allTypeBytes as $typebyte )
    print_r( $typebyte->getData() ) ;

print_r( $allTypeBytes ) ;

$allTicketStatuses = new TicketStatuses() ;

do { print_r( $allTicketStatuses->current()->getData() ) ;
} while ( $allTicketStatuses->next() !== false ) ;

for ( $i = 0 ; $i < $allTicketStatuses->count() ; $i++ ) 
    print_r( $allTicketStatuses( $i )->getData() ) ;

foreach ( $allTicketStatuses as $ticketstatus )
    print_r( $ticketstatus->getData() ) ;

print_r( $allTicketStatuses ) ;

?>
