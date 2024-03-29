<?php   namespace Stader\Bootstrap ;

echo '<pre>' ;
echo \PHP_EOL . str_repeat( '-' , 50 ) . \PHP_EOL . '-> entering : ' . basename( __file__ ) . \PHP_EOL ;

/*

create table if not exists area
(
    id      int auto_increment ,
    name    varchar(255) not null primary key ,
        constraint unique (name) ,
    description text
) ;

 */


/*
 *  setup
 */

require_once( dirname( __dir__ ) . '/classloader.php' ) ;
use \Stader\Model\Tables\Area\{Area,Areas} ;

/*
 *  data
 */

//     [ 'name' => '' , 'description' => '' ] ,
$areas =
[
    [ 'name' => 'A' , 'description' => 'Danchells anlæg' ] ,
    [ 'name' => 'B' , 'description' => 'Nordlandspladsen' ] ,
    [ 'name' => 'C' , 'description' => 'Cirkuspladsen' ] ,
    [ 'name' => 'D' , 'description' => 'Ved Brandstationen' ] ,
    [ 'name' => 'E' , 'description' => 'Kampeløkke Havn' ] ,
    [ 'name' => 'F' , 'description' => 'Ved Allinge Røgeri' ] ,
    [ 'name' => 'G' , 'description' => 'Yder-/indermolen' ] ,
    [ 'name' => 'H' , 'description' => 'Allinge Havn' ] ,
    [ 'name' => 'J' , 'description' => 'Kæmpestranden' ] ,
    [ 'name' => 'S' , 'description' => 'Kirkepladsen ' ] ,
    [ 'name' => 'N' , 'description' => 'Nørregade ' ] 
] ;

/*
 *  main
 */
// exit( 'tidlig exit under test' . \PHP_EOL ) ;

( new Areas() )->deleteAll() ;

foreach ( $areas as $area )
{
    $thisArea = new Area( $area ) ;
} unset( $area) ;

foreach ( ( new Areas() ) as $area ) 
    echo json_encode( $area->getData() , JSON_UNESCAPED_UNICODE ) . \PHP_EOL ;
    unset( $area ) ;

/*
$areasAll[0]->setValues(['name'=>'T']) ;
$areasAll[1]->setValues(['description'=>'SydLandet']) ;
$areasAll[2]->setValues(['description'=>'TestVærdi','name'=>'Q']) ;
( end( $areasAll ) )->delete() ;
*/

echo '</pre>' ;
?>

