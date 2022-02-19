<?php namespace Stader\HTML ;
require_once( dirname( __file__ , 2 ) . '/php/settings/phpValues.php') ;

   $include_paths[] = dirname( __DIR__ ) ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/cdn/php' ;
// $include_paths[] =  '.' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/cdn/_/php' ;
set_include_path( implode( ':' , $include_paths ) ) ;

// echo 'IncludePaths : ' . \PHP_EOL ;
// print_r( explode( ':' , get_include_path() ) ) ;

require_once( dirname( __file__ , 2 ) . '/php/model/class.classloader.php' ) ;
require_once( dirname( __file__ , 2 ) . '/php/control/class.classloader.php' ) ;

use \Stader\Model\Tables\Ticket\{Tickets,TicketLog,TicketLogs} ;

$format = 'csv' ;
$problemer = new Tickets() ;
foreach ( $problemer as $problem )
{
//     echo str_repeat( '-', 50 ) . \PHP_EOL ;
//     error_log( print_r( $problem->getData() , true ) ) ;
//     print_r( $problem->getData() ) ;

    $headers = [] ;
    $denneLogs = new TicketLogs( 'ticket_id' , $problem->getData()['ticket_id'] ) ;

    $fileName = "dump_" . $problem->getData()['ticket_id'] . ".{$format}" ;
    header("Content-Type: text/csv") ;
    header("Content-Disposition: attachment; filename={$fileName}") ;

    if ( ! $fileHandle = fopen( "php://output" , "w" )  ) 
        die( "!!! kunne ikke åbne {$fileName} for skrivning !!!" . \PHP_EOL ) ;

    foreach ( $denneLogs as $problemLog )
    {
        $thisLog = new TicketLog( $problemLog ) ;

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
