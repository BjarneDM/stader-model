#!/opt/local/bin/php
<?php namespace stader\sql ;

if ( in_array( $argv[1], [ '-h' , '--help' ] ) )
{   echo <<<'HELP'
/*
 *  $argv[1] : output type
 *  $argv[2] : tabel navn
 */

HELP;
exit() ; }

   $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/stader/php' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/cdn/php' ;
// $include_paths[] =  '.' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/cdn/_/php' ;
set_include_path( implode( ':' , $include_paths ) ) ;

// echo 'IncludePaths : ' . \PHP_EOL ;
// print_r( explode( ':' , get_include_path() ) ) ;

require_once(  dirname( __file__ , 2 ) . '/php/model/class.classloader.php' ) ;


$format = ( in_array( $argv[1] , [ 'json' , 'csv' ] ) ) ?: 'json' ;

use \stader\model\{Setup} ;

$dbh = ( new Setup() )->getDBH() ;

$sql  = "select * from {$argv[2]} " ;
$stmt = $dbh->prepare( $sql ) ;
$stmt->execute() ;

$fileName = "dump_{$argv[2]}.{$format}" ;
if ( ! $fileHandle = fopen( $fileName , "w" )  ) 
    die( "!!! kunne ikke åbne {$fileName} for skrivning !!!" . \PHP_EOL ) ;
switch ( $format )
{
    case 'json' :
        while ( $row = $stmt->fetch( \PDO::FETCH_ASSOC ) )
        {
            $dataJson = json_encode( $row ) ;
            if ( fwrite( $fileHandle , $dataJson . \PHP_EOL ) === false )
                die( "!!! kunne ikke skrive til {$fileName} !!!" . \PHP_EOL ) ;
//             echo fwrite( $fileHandle , $dataJson . \PHP_EOL ) . \PHP_EOL ;
        }
        break ;
    case 'csv'  :
        $head = true ;
        while ( $row = $stmt->fetch( \PDO::FETCH_ASSOC ) )
        {
            if ( $head )
            {
                $heading = array_keys( $row ) ;
                if ( fputcsv( $fileHandle , $heading ) === false )
                    die( "!!! heading : kunne ikke skrive til {$fileName} !!!" . \PHP_EOL ) ;
//                 echo fwrite( $fileHandle , implode( ' ; ' , $heading ) . \PHP_EOL ) . \PHP_EOL ;
            $head = false ; }
            $values = array_values( $row ) ;
            if ( fputcsv( $fileHandle , $values ) === false )
                die( "!!! data : kunne ikke skrive til {$fileName} !!!" . \PHP_EOL ) ;
//             echo fwrite( $fileHandle , implode( ' ; ' , $values ) . \PHP_EOL ) . \PHP_EOL ;
        }
        break ;
}

?>
