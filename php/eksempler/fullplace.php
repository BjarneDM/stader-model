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

echo '<pre>' . \PHP_EOL ;

$areas = new Areas() ;
foreach ( $areas as $area )
{
    $places = new Places( 'area_id' , (int) $area->getData()['area_id'] ) ;
    foreach ( $places as $place )
    {
        $fullPlace = new Place( array_merge( $area->getData() , $place->getData() ) ) ;
//         print_r( $fullPlace->getData() ) ;
        print_r( [ 'full_place' => $fullPlace->getData()['full_place'] ] ) ;
    }
}

echo '</pre>' . \PHP_EOL ;

?>
