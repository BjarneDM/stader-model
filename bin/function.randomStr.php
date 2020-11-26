#!/opt/local/bin/php
<?php
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

set_include_path( '/Volumes/Bjarne/Sites/info/mathiesen/zbc/stader/php' ) ;
require_once( dirname( __file__ , 2 ) . '/php/functions/randomStr.php' ) ;

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
