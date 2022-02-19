<?php namespace Stader\Eksempler ;

   $include_paths[] = dirname( __DIR__ ) ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/cdn/php' ;
// $include_paths[] =  '.' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/cdn/_/php' ;
set_include_path( implode( ':' , $include_paths ) ) ;

// echo 'IncludePaths : ' . \PHP_EOL ;
// print_r( explode( ':' , get_include_path() ) ) ;

require_once( 'classloader.php' ) ;
require_once( dirname( __file__ , 2 ) . '/control/class.classloader.php' ) ;

use \Stader\Model\Tables\Ticket\{Ticket,Tickets,TicketLog,TicketLogs} ;

$format = 'csv' ;
$problemer = new Tickets() ;
foreach ( $problemer as $problem )
{
    echo str_repeat( '-', 50 ) . \PHP_EOL ;
    print_r( $problem->getData() ) ;

    $headers = [] ;
    $denneLogs = new TicketLogs( 'ticket_id' , $problem->getData()['ticket_id'] ) ;

    $fileName = "dump_" . $problem->getData()['ticket_id'] . ".{$format}" ;
    if ( ! $fileHandle = fopen( $fileName , "w" )  ) 
        die( "!!! kunne ikke åbne {$fileName} for skrivning !!!" . \PHP_EOL ) ;

    foreach ( $denneLogs as $problemLog )
    {
        $thisLog = new TicketLog( $problemLog ) ;

        if ( empty( $headers ) ) 
        {
            $headers = $thisLog->getKeys() ;
            if ( fwrite( $fileHandle , implode( ' ; ' , $headers ) . ';' . \PHP_EOL ) === false )
                die( "!!! heading : kunne ikke skrive til {$fileName} !!!" . \PHP_EOL ) ;
        }
        $values = [] ;

        foreach ( $headers as $key ) 
        {
            $values[] = $thisLog->getData()[ $key ] ;
        }
        if ( fwrite( $fileHandle , implode( ' ; ' , $values ) . ';' . \PHP_EOL ) === false )
            die( "!!! data : kunne ikke skrive til {$fileName} !!!" . \PHP_EOL ) ;
        
    } 
}

?>
