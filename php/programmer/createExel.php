<?php namespace stader\programmer ;

   $include_paths[] =  dirname( __file__ , 3) . '/php' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/cdn/php' ;
// $include_paths[] =  '.' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/cdn/_/php' ;
set_include_path( implode( ':' , $include_paths ) ) ;

// echo 'IncludePaths : ' . \PHP_EOL ;
print_r( explode( ':' , get_include_path() ) ) ; exit ;

$folder = 'csvFiles' ;
if (    ( ! is_dir( $folder ) ) 
     && ( ! mkdir( $folder , 0777 ) ) ) 
    die ( "kunne ikke oprette mappen {$folder}" ) ;

$xlsx =
    [
        'dbase' => 'allowedClasses' ,
        'logs'  => 'allowedLogs'
    ] ;

$type = ( in_array( $argv[1] , array_keys( $xlsx ) ) ) ? $argv[1] : 'logs' ;
$classType = $xlsx[ $type ] ;

require_once( dirname( __file__ , 2 ) . '/control/class.classloader.php' ) ;
require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;

use \stader\model\{MInstances} ;

// https://github.com/PHPOffice/PhpSpreadsheet
require_once( dirname( __file__ , 2 ) . '/model/phpspreadsheet/autoload.php' ) ;

use PhpOffice\PhpSpreadsheet\Spreadsheet ;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx ;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet ;
use PhpOffice\PhpSpreadsheet\Reader\Csv ;

$spreadsheet = new Spreadsheet();

$classes = MInstances::${$classType} ;
foreach ( $classes as $thisClass )
{

    $instances = MInstances::getObjects( $thisClass ) ;

    $fileName = "{$folder}/dump_{$thisClass}.csv" ;
    if ( ! $fileHandle = fopen( $fileName , "w" )  ) 
        die( "!!! kunne ikke åbne {$fileName} for skrivning !!!" . \PHP_EOL ) ;

    $myWorkSheet = new Worksheet( $spreadsheet ,  $thisClass ) ;
    $spreadsheet->addSheet( $myWorkSheet , 0 ) ;

    $headers = [] ;
    foreach ( $instances->getAll() as $classInstance )
    {
        if ( empty( $headers ) ) 
        {
            $headers = $classInstance->getKeys() ;
            if ( fputcsv( $fileHandle , $headers ) === false )
                die( "!!! heading : kunne ikke skrive til {$fileName} !!!" . \PHP_EOL ) ;
        }

        $values = [] ;
        foreach ( $headers as $key ) 
        {
            $values[] = $classInstance->getData()[ $key ] ;
        }
        if ( fputcsv( $fileHandle , $values ) === false )
            die( "!!! data : kunne ikke skrive til {$fileName} !!!" . \PHP_EOL ) ;

        $reader = new Csv() ;
        $reader->setDelimiter( ',' ) ;
        $reader->setEnclosure( '"' ) ;
        $reader->setSheetIndex( 0 )  ;

        $reader->loadIntoExisting( $fileName , $spreadsheet ) ;
    }
    
}

$writer = new Xlsx( $spreadsheet ) ;
$writer->save( "bornholm{$type}.xlsx" ) ;

?>
