<?php namespace Stader\Eksempler ;

   $include_paths[] = dirname( __DIR__ ) ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/cdn/php' ;
// $include_paths[] =  '.' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/cdn/_/php' ;
// set_include_path( implode( ':' , $include_paths ) ) ;

// echo 'IncludePaths : ' . \PHP_EOL ;
// print_r( explode( ':' , get_include_path() ) ) ;

require_once( 'classloader.php' ) ;

use \Stader\Model\Tables\Place\{Place} ;
use \Stader\Model\Tables\Area\{Area} ;
use \Stader\Model\Tables\Ticket\{Ticket,Tickets,TicketLog,TicketLogs} ;

list( $areaName , $placeNr ) = preg_split( '//', $argv[1] , 2 , PREG_SPLIT_NO_EMPTY ) ;
$areaID = ( new Area( 'name' , $areaName ) )->getData()['area_id'] ;
$place  = new Place( [ 'area_id' , 'place_nr' ] , [ $areaID , $placeNr ] ) ;
print_r( $place->getData() ) ;

$placeTickets = new Tickets( 'assigned_place_id' , (string) $place->getData()['place_id'] ) ;
// print_r( $placeTickets->getAll() ) ;

foreach ( $placeTickets as $thisTicket )
{
    print_r( $thisTicket->getData() ) ;

    $thisTicketLogs = new TicketLogs( 'ticket_id' , $thisTicket->getData()['ticket_id'] ) ;
//     print_r( $thisTicketLogs->getAll() ) ;

    list( $opretHeader , $opdaterHeader , $slettetHeader ) = [ false , false , false ] ;
    foreach ( $thisTicketLogs as $thisLog ) 
    {
        print_r( $thisLog->getData() ) ;

        // der er kun én af denne pr. $thisTicket & den står 1st
        if ( $thisLog->getData()['header'] == 'oprettet' )
        {
            if ( $opretHeader ) { $opdaterHeader = true ; } // udskriv header
            // udskriv værdierne
            $origTicket = json_decode( $thisLog->getData()['new_value'] , true ) ;
            print_r( $origTicket ) ;
        }  

        if ( strpos( $thisLog->getData()['header'] , 'opdateret' ) === 0 )
        {
            if ( $opdaterHeader ) { $opdaterHeader = true ; } // udskriv header
            // udskriv værdierne
        }  

        // der er kun én af denne pr. $thisTicket & den står sidst
        if ( $thisLog->getData()['header'] == 'slettet' )
        {
            if ( $slettetHeader ) { $slettetHeader = true ; } // udskriv header
            //udskriv værdierne
            $finalTicket = json_decode( $thisLog->getData()['old_value'] , true ) ;
        }  

    }
}

?>
