<?php namespace Stader\HTML ;

   $include_paths[] = dirname( __DIR__ ) . '/php' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/cdn/php' ;
// $include_paths[] =  '.' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/cdn/_/php' ;
set_include_path( implode( ':' , $include_paths ) ) ;

// echo 'IncludePaths : ' . \PHP_EOL ;
// print_r( explode( ':' , get_include_path() ) ) ;

require_once( 'settings/phpValues.php') ;
require_once( 'classloader.php' ) ;

use \Stader\Model\Tables\Area\{Area} ;
use \Stader\Model\Tables\Place\{Places,PlaceLog,PlaceLogs} ;
use \Stader\Model\Tables\Ticket\{Tickets,TicketLog,TicketLogs} ;

$format = 'csv' ;
$stader = new Places() ;
foreach ( $stader as $stade )
{
//     echo str_repeat( '-', 50 ) . \PHP_EOL ;
//     error_log( print_r( $stade->getData() , true ) );

    $headers = [] ;
    $fullplace = ( new Area( $stade->getData()['area_id'] ) )->getData()['name'] . $stade->getData()['place_nr'] ;
    $fullplace = 'A1' ; // testværdi
    $denneLogs = new PlaceLog( 'full_place' , $fullplace ) ;

    $fileName = "dump_" . $stade->getData()['ticket_id'] . ".{$format}" ;
    header("Content-Type: text/csv") ;
    header("Content-Disposition: attachment; filename={$fileName}") ;

    if ( ! $fileHandle = fopen( "php://output" , "w" )  ) 
        die( "!!! kunne ikke åbne {$fileName} for skrivning !!!" . \PHP_EOL ) ;

    foreach ( $denneLogs as $stadeLog )
    {

        switch ( count( json_decode( $stadeLog->getData()['data'] , true ) ) )
        {
            case  7 :
                $thisLog = new \Stader\Control\PlaceLog( $stadeLog ) ;
                break ;
            case 11 :
                $thisLog = new \Stader\Control\TicketLog( $stadeLog ) ;
                break ;
        } continue ;

        if ( empty( $headers ) ) 
        {
            $headers = $thisLog->getKeys() ;
            if ( fputcsv( $fileHandle , $headers ) === false )
                die( "!!! heading : kunne ikke skrive til {$fileName} !!!" . \PHP_EOL ) ;
        }



        $values = [] ;

        foreach ( $headers as $key ) 
        {
            $values[] = $thisLog->getData()[ $key ] ;
        }
        if ( fputcsv( $fileHandle , $values ) === false )
            die( "!!! data : kunne ikke skrive til {$fileName} !!!" . \PHP_EOL ) ;
        
    }  exit() ;
}

?>
