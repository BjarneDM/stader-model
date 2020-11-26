<?php namespace stader\programmer ;

$folder = 'csvFiles' ;
if (    ( ! is_dir( $folder ) ) 
     && ( ! mkdir( $folder , 0777 ) ) ) 
    die ( "kunne ikke oprette mappen {$folder}" ) ;

$xlsx =
    [
        'dbase' => 'allowedClasses' ,
        'logs'  => 'allowedLogs'
    ] ;

require_once( dirname( __file__ , 2 ) . '/php/control/class.classloader.php' ) ;
require_once( dirname( __file__ , 2 ) . '/php/model/class.classloader.php' ) ;

use \stader\model\{MInstances} ;

// https://github.com/PHPOffice/PhpSpreadsheet
require_once( dirname( __file__ , 2 ) . '/php/model/phpspreadsheet/autoload.php' ) ;

use PhpOffice\PhpSpreadsheet\Spreadsheet ;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx ;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet ;
use PhpOffice\PhpSpreadsheet\Reader\Csv ;

$spreadsheet = new Spreadsheet();

foreach ( $xlsx as $type => $classType )
{
    header("Content-Type: text/csv") ;
    header("Content-Disposition: attachment; filename=bornholm{$type}.xlsx") ;


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

    if ( ! $fileHandle = fopen( "php://output" , "w" )  ) 
        die( "!!! kunne ikke åbne {$fileName} for skrivning !!!" . \PHP_EOL ) ;

    $writer = new Xlsx( $spreadsheet ) ;
    $writer->save( $fileHandle ) ;

    fclose( $fileHandle ) ;

}
?>
