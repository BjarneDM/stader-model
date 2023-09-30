<?php namespace Stader\Eksempler ;

/*

create table if not exists areas
(
    area_id     int auto_increment primary key ,
    name        varchar(255) not null ,
        constraint unique (name) ,
    description text
) ;

create table if not exists place
(
    place_id        int auto_increment primary key ,
    place_nr        varchar(8) not null ,
    description     text ,
    place_owner_id  int ,
    area_id         int ,
    lastchecked     datetime
) ;

 */

   $include_paths[] = dirname( __DIR__ ) ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/cdn/php' ;
// $include_paths[] =  '.' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/cdn/_/php' ;
set_include_path( implode( ':' , $include_paths ) ) ;

// echo 'IncludePaths : ' . \PHP_EOL ;
// print_r( explode( ':' , get_include_path() ) ) ;

require_once( 'classloader.php' ) ;

use \Stader\Model\Tables\Area\{Area,Areas} ;
use \Stader\Model\Tables\Place\{Place,Places} ;

use \Stader\Control\Objects\AreaPlace\{AreaPlace,AreasPlaces} ;

// $testPlace = new AreaPlace( 'A1' ) ;
// print_r( $testPlace->getData() ) ;
// print_r( ( new AreaPlace( 'B2' ) )->getData() ) ;

echo '<pre>' . \PHP_EOL ;

/*
foreach ( ( new Areas( 'description' , 'Kirkepladsen' ) ) as $area )
{
    foreach ( ( new Places( 'area_id' , $area->getData()['id'] ) ) as $place )
    {
        print_r( ( 
            new AreaPlace( 
                $area->getData()['name'] . 
                $place->getData()['place_nr'] 
        ) )->getData() ) ;
    }
}
*/

/*
foreach ( ( new AreasPlaces( 'description' , 'Kirkepladsen' ) ) as $areaplace )
    {
        // print_r( $areaplace->getData() ) ;
        echo $areaplace . PHP_EOL ;
    } unset( $areaplace ) ;
*/

/*
$areas = new Areas('description' , 'Kirkepladsen') ;
$areas->setOrderBy( ['description' => 'asc' ] ) ;
foreach ( $areas as $area )
{
    $places = new Places( 'area_id' , $area->getData()['id'] ) ;
    $places->setOrderBy( ['description' => 'desc' ] ) ;
    foreach ( $places as $place )
    {
        print_r( ( 
            new AreaPlace( 
                $area->getData()['name'] . 
                $place->getData()['place_nr'] 
        ) )->getData() ) ;
    }
}
*/

$areaplaces = new AreasPlaces() ;
// $areaplaces->setOrderBy( ['description' => 'asc' ] ) ;
foreach ( $areaplaces as $areaplace )
    {
        // print_r( $areaplace->getData() ) ;
        echo $areaplace . PHP_EOL ;
    } unset( $areaplace ) ;

echo '</pre>' . \PHP_EOL ;

?>
