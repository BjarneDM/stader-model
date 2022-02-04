#!/usr/bin/env php
<?php namespace functions ;
error_reporting(0) ;
// print_r( [ $argc , $argv ] ) ;

set_include_path( dirname( __DIR__ ) . '/php' ) ;
require_once( dirname( __DIR__ ) . '/php/classloader.php' ) ;
use \Stader\Model\{RandomStr} ;

/*
 *  help functions
 */
if ( in_array( $argv[1], [ '-h' , '--help' ] ) )
{   echo <<<'HELP'

[ '-a' , '--args' ]      : list argument variables
[ '-k' , '--keyspaces' ] : list available keyspaces


HELP;
exit() ; }

if ( in_array( $argv[1], [ '-a' , '--args' ] ) )
{   echo <<<'HELP'

$argv[1] : # of passwords to print ( default :  5 )
$argv[2] : length of passwords     ( default : 24 )
$argv[3] : alfabeth complexity     ( default :  1 )


HELP;
exit() ; }

if ( in_array( $argv[1], [ '-k' , '--keyspaces' ] ) )
{
    ( new RandomStr() )->getKeyspaces() ;
exit() ; }

/*
 *  main
 */
$randomStr = new RandomStr(  
    [ 
        'length' => (( $argv[2] ) ?: 24) , 
        'ks'     => (( $argv[3] ) ?:  1) 
    ] ) ;
for ( $i = 0 ; 
      $i < ( $argv[1] ?: 5 ) ; 
      $i++ 
    )
    echo $randomStr->next() . \PHP_EOL ;

?>
