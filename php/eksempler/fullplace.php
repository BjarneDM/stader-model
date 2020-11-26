<?php namespace stader\eksempler ;

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

   $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/stader/php' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/cdn/php' ;
// $include_paths[] =  '.' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/cdn/_/php' ;
set_include_path( implode( ':' , $include_paths ) ) ;

// echo 'IncludePaths : ' . \PHP_EOL ;
// print_r( explode( ':' , get_include_path() ) ) ;

require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;
require_once( dirname( __file__ , 2 ) . '/control/class.classloader.php' ) ;

use \stader\model\{Area,Areas,Places} ;

echo '<pre>' . \PHP_EOL ;

$areas = new Areas() ;
foreach ( $areas->getAreas() as $area )
{
    $places = new Places( 'area_id' , (int) $area->getData()['area_id'] ) ;
    foreach ( $places->getPlaces() as $place )
    {
        $fullPlace = new \stader\control\Place( array_merge( $area->getData() , $place->getData() ) ) ;
//         print_r( $fullPlace->getData() ) ;
        print_r( [ 'full_place' => $fullPlace->getData()['full_place'] ] ) ;
    }
}

echo '</pre>' . \PHP_EOL ;

?>
