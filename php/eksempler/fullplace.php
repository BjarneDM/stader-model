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
use \Stader\Model\Tables\PlaceOwner\{PlaceOwner,PlaceOwners} ;

echo '<pre>' . \PHP_EOL ;

/*
 *  så problemet m/ nestede \Iterator dukker op igen
 *
foreach ( ( new Areas() ) as $area )
{
    foreach ( ( new Places( 'area_id' , $area->getData()['id'] ) ) as $place )
    {
            $values['area']       = $area->getData()  ;
            $values['place']      = $place->getData() ;
            $values['placename']  = $area->getData()['name'] . $place->getData()['place_nr'] ;
            $owner                = new PlaceOwner( $place->getData()['place_owner_id'] ) ;
            $values['placeowner'] = $owner->getData() ;
            print_r( [ 'full_place' => $values ] ) ;
    }
}
 */
foreach ( ( new Areas() )->getIDs() as $areaID )
{
            $area                 = new Area( $areaID ) ;
            $values['area']       = $area->getData()  ;
    foreach ( ( new Places( 'area_id' , $area->getData()['id'] ) ) as $place )
    {
            $values['place']      = $place->getData() ;
            $values['placename']  = $area->getData()['name'] . $place->getData()['place_nr'] ;
            $owner                = new PlaceOwner( $place->getData()['place_owner_id'] ) ;
            $values['placeowner'] = $owner->getData() ;
            print_r( [ 'full_place' => $values ] ) ;
    }
}


echo '</pre>' . \PHP_EOL ;

?>
