<?php   namespace Stader\Bootstrap ;

echo '<pre>' ;
echo \PHP_EOL . str_repeat( '-' , 50 ) . \PHP_EOL . '-> entering : ' . basename( __file__ ) . \PHP_EOL ;



/*

drop table if exists place ;
create table if not exists place
(
    id              int auto_increment primary key ,
    place_nr        varchar(8) not null ,
    description     text ,
    place_owner_id  int ,
        foreign key (place_owner_id) references place_owner(id)
        on update cascade 
        on delete restrict ,
    area_id         int ,
        foreign key (area_id) references areas(id)
        on update cascade 
        on delete cascade ,
    lastchecked     datetime
        default   current_timestamp
        on update current_timestamp
) ;

 */


/*
 *  setup
 */

require_once( dirname( __dir__ ) . '/classloader.php' ) ;
use \Stader\Model\Tables\Area\{Area} ;
use \Stader\Model\Tables\Place\{Place,Places} ;
use \Stader\Model\Tables\PlaceOwner\{PlaceOwner} ;

/*
 *  data
 */

//     [ 'place_nr' => '' , 'area_id' => '' , 'place_owner_id' => '' , 'description' => '' ] ,
$places =
[ 
    [ 'active' => true  , 'place_nr' => '3' , 'area_id' => 'C' , 'place_owner_id' => null , 'header' => 'SF' ] ,
    [ 'active' => true  , 'place_nr' => '4' , 'area_id' => 'C' , 'place_owner_id' => null , 'header' => 'KristenDemokraterne' ] ,
    [ 'active' => true  , 'place_nr' => '1' , 'area_id' => 'C' , 'place_owner_id' => null , 'header' => 'Dansk Folkeparti' ] ,
    [ 'active' => true  , 'place_nr' => '5' , 'area_id' => 'C' , 'place_owner_id' => null , 'header' => 'Enhedslisten' ] ,
    [ 'active' => true  , 'place_nr' => '6' , 'area_id' => 'C' , 'place_owner_id' => null , 'header' => 'Radikale Venstre' ] ,
    [ 'active' => true  , 'place_nr' => '1' , 'area_id' => 'D' , 'place_owner_id' => null , 'header' => 'Brandstationens Debattelt' ] ,
    [ 'active' => true  , 'place_nr' => '2' , 'area_id' => 'D' , 'place_owner_id' => null , 'header' => 'Bedsteforældre for Asyl' ] ,
    [ 'active' => true  , 'place_nr' => '3' , 'area_id' => 'D' , 'place_owner_id' => null , 'header' => 'Nye Borgerlige' ] ,
    [ 'active' => true  , 'place_nr' => '4' , 'area_id' => 'D' , 'place_owner_id' => null , 'header' => 'Café Brandstationen' ] ,
    [ 'active' => true  , 'place_nr' => '5' , 'area_id' => 'F' , 'place_owner_id' => null , 'header' => 'MUST' ] ,
    [ 'active' => true  , 'place_nr' => '5' , 'area_id' => 'D' , 'place_owner_id' => null , 'header' => 'Socialdemokratiet' ] ,
    [ 'active' => true  , 'place_nr' => '6' , 'area_id' => 'D' , 'place_owner_id' => null , 'header' => 'FOA og Pensam' ] ,
    [ 'active' => true  , 'place_nr' => '1' , 'area_id' => 'A' , 'place_owner_id' => null , 'header' => 'Verdensmålenes Plads' ] ,
    [ 'active' => true  , 'place_nr' => '8' , 'area_id' => 'B' , 'place_owner_id' => null , 'header' => 'Energibyerne.dk' ] ,
    [ 'active' => false , 'place_nr' => '9' , 'area_id' => 'B' , 'place_owner_id' => null , 'header' => 'iLoveGolbalGoals' ] ,
    [ 'active' => true  , 'place_nr' => '6' , 'area_id' => 'E' , 'place_owner_id' => null , 'header' => 'Ann Thai' ] ,
    [ 'active' => true  , 'place_nr' => '7' , 'area_id' => 'E' , 'place_owner_id' => null , 'header' => 'Tante Toast' ] ,
    [ 'active' => true  , 'place_nr' => '1' , 'area_id' => 'E' , 'place_owner_id' => null , 'header' => 'Sinaturs Mad- og Mødested' ] ,
    [ 'active' => true  , 'place_nr' => '2' , 'area_id' => 'E' , 'place_owner_id' => null , 'header' => 'Sinaturs Mødested' ] ,
    [ 'active' => true  , 'place_nr' => '3' , 'area_id' => 'E' , 'place_owner_id' => null , 'header' => 'Debat og ØMad' ] ,
    [ 'active' => true  , 'place_nr' => '8' , 'area_id' => 'E' , 'place_owner_id' => null , 'header' => 'Medieteltet og Radio Folkemødet' ] ,
    [ 'active' => true  , 'place_nr' => '1' , 'area_id' => 'F' , 'place_owner_id' => null , 'header' => 'BornholmsLinjen' ] ,
    [ 'active' => true  , 'place_nr' => '2' , 'area_id' => 'F' , 'place_owner_id' => null , 'header' => 'Efterladen' ] ,
    [ 'active' => true  , 'place_nr' => '4' , 'area_id' => 'F' , 'place_owner_id' => null , 'header' => 'Stik piben ind og lyt' ] ,
    [ 'active' => true  , 'place_nr' => '6' , 'area_id' => 'F' , 'place_owner_id' => null , 'header' => 'Menneskerettighedsteltet' ] ,
    [ 'active' => true  , 'place_nr' => '2' , 'area_id' => 'C' , 'place_owner_id' => null , 'header' => 'Det Konservative Folkeparti' ] ,
    [ 'active' => true  , 'place_nr' => '7' , 'area_id' => 'F' , 'place_owner_id' => null , 'header' => 'KL\'s telt' ] ,
    [ 'active' => false , 'place_nr' => '1' , 'area_id' => 'S' , 'place_owner_id' => null , 'header' => 'Apoteltet' ] ,
    [ 'active' => true  , 'place_nr' => '3' , 'area_id' => 'S' , 'place_owner_id' => null , 'header' => 'Coop Salgsbod' ] ,
    [ 'active' => true  , 'place_nr' => '4' , 'area_id' => 'S' , 'place_owner_id' => null , 'header' => 'Coop Eventtelt' ] ,
    [ 'active' => true  , 'place_nr' => '5' , 'area_id' => 'S' , 'place_owner_id' => null , 'header' => 'Coop CoCook' ] ,
    [ 'active' => true  , 'place_nr' => '6' , 'area_id' => 'S' , 'place_owner_id' => null , 'header' => 'Folkets Hus' ] ,
    [ 'active' => true  , 'place_nr' => '7' , 'area_id' => 'S' , 'place_owner_id' => null , 'header' => 'Akademikernes Hus' ] ,
    [ 'active' => true  , 'place_nr' => '2' , 'area_id' => 'A' , 'place_owner_id' => null , 'header' => 'FN Byen' ] ,
    [ 'active' => true  , 'place_nr' => '3' , 'area_id' => 'A' , 'place_owner_id' => null , 'header' => 'Forsvaret' ] ,
    [ 'active' => true  , 'place_nr' => '4' , 'area_id' => 'A' , 'place_owner_id' => null , 'header' => 'Ungdomshøjen' ] ,
    [ 'active' => true  , 'place_nr' => '5' , 'area_id' => 'A' , 'place_owner_id' => null , 'header' => 'Højskolerns Folkekøkken' ] ,
    [ 'active' => true  , 'place_nr' => '6' , 'area_id' => 'A' , 'place_owner_id' => null , 'header' => 'Landdistrikternes Telt' ] ,
    [ 'active' => true  , 'place_nr' => '1' , 'area_id' => 'B' , 'place_owner_id' => null , 'header' => 'Advice Scenen' ] ,
    [ 'active' => false , 'place_nr' => '2' , 'area_id' => 'B' , 'place_owner_id' => null , 'header' => 'Klima & Energi Scenen' ] ,
    [ 'active' => true  , 'place_nr' => '3' , 'area_id' => 'B' , 'place_owner_id' => null , 'header' => 'Timm Vladimirs Køkken' ] ,
    [ 'active' => true  , 'place_nr' => '6' , 'area_id' => 'B' , 'place_owner_id' => null , 'header' => 'Klima & Energi Teltet' ] 
] ;

/*
 *  main
 */

( new Places() )->deleteAll() ;

foreach ( $places as $key => $place )
{
//     print_r( $place ) ;
//     $place['place_owner_id'] = (int) ( new PlaceOwner( 'name' , 'dummy' ) )->getData()['place_owner_id'] ;
    $place['area_id']        = (int) ( new Area( 'name'  , $place['area_id'] ) )->getData()['id'] ;
    $thisPlace               = new Place( $place ) ;
}   unset( $key , $place) ;

$placeOwners =
[
    'Slagelse'  => 'A1' ,
    'Nielsen' => 'S1' ,
    'Kristensen' => 'C3' ,
    'Rasmussen' => 'D5' ,
    'Starbech' => 'B3' ,
] ;

foreach ( $placeOwners as $username => $area )
{
    list( $areaName , $placeNr ) = preg_split( '//', $area , 2 , PREG_SPLIT_NO_EMPTY ) ;
    $areaID = ( new Area( 'name' , $areaName ) )->getData()['id'] ;
    $placeownerID = ( new PlaceOwner( 'surname' , $username ) )->getData()['id'] ;
    $place  = new Place( ['area_id','place_nr'] , [$areaID,(int)$placeNr] ) ;
    $place->setValues(['place_owner_id' => $placeownerID]) ;
}

foreach ( ( new Places() ) as $place ) 
    echo json_encode( $place->getData() , JSON_UNESCAPED_UNICODE ) . \PHP_EOL ;
    unset( $place ) ;

echo '</pre>' ;
?>

