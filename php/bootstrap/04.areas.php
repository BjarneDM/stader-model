<?php   namespace stader\bootstrap ;

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

require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;
use \stader\model\{Area,Areas} ;

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

foreach ( $areas as $area )
{
    $thisArea = new Area( $area ) ;
} unset( $area) ;

$allAreas = new Areas() ;
foreach ( ( $areasAll = $allAreas->getAll() ) as $area ) 
    echo json_encode( $area->getData() , JSON_UNESCAPED_UNICODE ) . \PHP_EOL ;
    unset( $area ) ;

$areasAll[0]->setValues(['name'=>'T']) ;
$areasAll[1]->setValues(['description'=>'SydLandet']) ;
$areasAll[2]->setValues(['description'=>'TestVærdi','name'=>'Q']) ;
( end( $areasAll ) )->delete() ;

echo \PHP_EOL . str_repeat( '-' , 50 ) . \PHP_EOL  . \PHP_EOL ;

$allAreas = new Areas() ;
if ( $allAreas->count() > 0 )
{
    $allAreas->reset() ;
    do {
        echo json_encode( $allAreas->current()->getData() , JSON_UNESCAPED_UNICODE ) . \PHP_EOL ;
    }   while ( $allAreas->next() ) ;
}

echo '</pre>' ;
?>

