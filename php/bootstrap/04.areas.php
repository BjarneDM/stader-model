<?php   namespace stader\bootstrap ;

echo '<pre>' ;
echo \PHP_EOL . str_repeat( '-' , 50 ) . \PHP_EOL . '-> entering : ' . basename( __file__ ) . \PHP_EOL ;



/*

create table if not exists areas
(
    area_id     int auto_increment primary key ,
    name        varchar(255) not null ,
        constraint unique (place_nr) ,
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
    [ 'name' => 'E' , 'description' => 'Kampeløkke HJavn' ] ,
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

foreach ( $areas as $area )
{
    $thisArea = new Area( $area ) ;
} unset( $area) ;

$allAreas = new Areas() ;
foreach ( $allAreas->getAreas() as $area ) 
    echo json_encode( $area->getData() , JSON_UNESCAPED_UNICODE ) . \PHP_EOL ;
    unset( $area ) ;

echo '</pre>' ;
?>

