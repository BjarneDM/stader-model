#!/usr/bin/env php
<?php
error_reporting(0) ;
// print_r( [ $argc , $argv ] ) ;

if ( in_array( $argv[1], [ '-h' , '--help' ] ) )
{   echo <<<'HELP'
/*
 *  $argv[1] : # of passwords to print ( default :  5 )
 *  $argv[2] : length of passwords     ( default : 24 )
 *  $argv[3] : alfabeth complexity     ( default :  1 )
 */

HELP;
exit() ; }

set_include_path( dirname( __DIR__) . '/php' ) ;
require_once( dirname( __DIR__) . '/php/functions/randomStr.php' ) ;

for ( $i = 0 ; 
      $i < ( $argv[1] ?: 5 ) ; 
      $i++ 
    )
    echo 
        randomStr( 
            (( $argv[2] ) ?: 24) , 
            (( $argv[3] ) ?:  1) ) 
        . PHP_EOL ;
    
?>
