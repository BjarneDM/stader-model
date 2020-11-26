<?php   namespace stader\bootstrap ;

echo '<pre>' ;
echo \PHP_EOL . str_repeat( '-' , 50 ) . \PHP_EOL . '-> entering : ' . basename( __file__ ) . \PHP_EOL ;



/*

drop table if exists place ;
create table if not exists place
(
    place_id        int auto_increment primary key ,
    place_nr        varchar(8) not null ,
    description     text ,
    place_owner_id  int ,
        foreign key (place_owner_id) references place_owner(place_owner_id)
        on update cascade 
        on delete restrict ,
    area_id         int ,
        foreign key (area_id) references areas(area_id)
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

require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;
use \stader\model\{User,Area,Place,Places,PlaceOwner} ;

/*
 *  data
 */

//     [ 'place_nr' => '' , 'area_id' => '' , 'place_owner_id' => '' , 'description' => '' ] ,
$places =
[ 
    [ 'active' => true  , 'place_nr' => '3' , 'area_id' => 'C' , 'place_owner_id' => null , 'description' => 'SF' ] ,
    [ 'active' => true  , 'place_nr' => '4' , 'area_id' => 'C' , 'place_owner_id' => null , 'description' => 'KristenDemokraterne' ] ,
    [ 'active' => true  , 'place_nr' => '1' , 'area_id' => 'C' , 'place_owner_id' => null , 'description' => 'Dansk Folkeparti' ] ,
    [ 'active' => true  , 'place_nr' => '5' , 'area_id' => 'C' , 'place_owner_id' => null , 'description' => 'Enhedslisten' ] ,
    [ 'active' => true  , 'place_nr' => '6' , 'area_id' => 'C' , 'place_owner_id' => null , 'description' => 'Radikale Venstre' ] ,
    [ 'active' => true  , 'place_nr' => '1' , 'area_id' => 'D' , 'place_owner_id' => null , 'description' => 'Brandstationens Debattelt' ] ,
    [ 'active' => true  , 'place_nr' => '2' , 'area_id' => 'D' , 'place_owner_id' => null , 'description' => 'Bedsteforældre for Asyl' ] ,
    [ 'active' => true  , 'place_nr' => '3' , 'area_id' => 'D' , 'place_owner_id' => null , 'description' => 'Nye Borgerlige' ] ,
    [ 'active' => true  , 'place_nr' => '4' , 'area_id' => 'D' , 'place_owner_id' => null , 'description' => 'Café Brandstationen' ] ,
    [ 'active' => true  , 'place_nr' => '5' , 'area_id' => 'F' , 'place_owner_id' => null , 'description' => 'MUST' ] ,
    [ 'active' => true  , 'place_nr' => '5' , 'area_id' => 'D' , 'place_owner_id' => null , 'description' => 'Socialdemokratiet' ] ,
    [ 'active' => true  , 'place_nr' => '6' , 'area_id' => 'D' , 'place_owner_id' => null , 'description' => 'FOA og Pensam' ] ,
    [ 'active' => true  , 'place_nr' => '1' , 'area_id' => 'A' , 'place_owner_id' => null , 'description' => 'Verdensmålenes Plads' ] ,
    [ 'active' => true  , 'place_nr' => '8' , 'area_id' => 'B' , 'place_owner_id' => null , 'description' => 'Energibyerne.dk' ] ,
    [ 'active' => false , 'place_nr' => '9' , 'area_id' => 'B' , 'place_owner_id' => null , 'description' => 'iLoveGolbalGoals' ] ,
    [ 'active' => true  , 'place_nr' => '6' , 'area_id' => 'E' , 'place_owner_id' => null , 'description' => 'Ann Thai' ] ,
    [ 'active' => true  , 'place_nr' => '7' , 'area_id' => 'E' , 'place_owner_id' => null , 'description' => 'Tante Toast' ] ,
    [ 'active' => true  , 'place_nr' => '1' , 'area_id' => 'E' , 'place_owner_id' => null , 'description' => 'Sinaturs Mad- og Mødested' ] ,
    [ 'active' => true  , 'place_nr' => '2' , 'area_id' => 'E' , 'place_owner_id' => null , 'description' => 'Sinaturs Mødested' ] ,
    [ 'active' => true  , 'place_nr' => '3' , 'area_id' => 'E' , 'place_owner_id' => null , 'description' => 'Debat og ØMad' ] ,
    [ 'active' => true  , 'place_nr' => '8' , 'area_id' => 'E' , 'place_owner_id' => null , 'description' => 'Medieteltet og Radio Folkemødet' ] ,
    [ 'active' => true  , 'place_nr' => '1' , 'area_id' => 'F' , 'place_owner_id' => null , 'description' => 'BornholmsLinjen' ] ,
    [ 'active' => true  , 'place_nr' => '2' , 'area_id' => 'F' , 'place_owner_id' => null , 'description' => 'Efterladen' ] ,
    [ 'active' => true  , 'place_nr' => '4' , 'area_id' => 'F' , 'place_owner_id' => null , 'description' => 'Stik piben ind og lyt' ] ,
    [ 'active' => true  , 'place_nr' => '6' , 'area_id' => 'F' , 'place_owner_id' => null , 'description' => 'Menneskerettighedsteltet' ] ,
    [ 'active' => true  , 'place_nr' => '2' , 'area_id' => 'C' , 'place_owner_id' => null , 'description' => 'Det Konservative Folkeparti' ] ,
    [ 'active' => true  , 'place_nr' => '7' , 'area_id' => 'F' , 'place_owner_id' => null , 'description' => 'KL\'s telt' ] ,
    [ 'active' => false , 'place_nr' => '1' , 'area_id' => 'S' , 'place_owner_id' => null , 'description' => 'Apoteltet' ] ,
    [ 'active' => true  , 'place_nr' => '3' , 'area_id' => 'S' , 'place_owner_id' => null , 'description' => 'Coop Salgsbod' ] ,
    [ 'active' => true  , 'place_nr' => '4' , 'area_id' => 'S' , 'place_owner_id' => null , 'description' => 'Coop Eventtelt' ] ,
    [ 'active' => true  , 'place_nr' => '5' , 'area_id' => 'S' , 'place_owner_id' => null , 'description' => 'Coop CoCook' ] ,
    [ 'active' => true  , 'place_nr' => '6' , 'area_id' => 'S' , 'place_owner_id' => null , 'description' => 'Folkets Hus' ] ,
    [ 'active' => true  , 'place_nr' => '7' , 'area_id' => 'S' , 'place_owner_id' => null , 'description' => 'Akademikernes Hus' ] ,
    [ 'active' => true  , 'place_nr' => '2' , 'area_id' => 'A' , 'place_owner_id' => null , 'description' => 'FN Byen' ] ,
    [ 'active' => true  , 'place_nr' => '3' , 'area_id' => 'A' , 'place_owner_id' => null , 'description' => 'Forsvaret' ] ,
    [ 'active' => true  , 'place_nr' => '4' , 'area_id' => 'A' , 'place_owner_id' => null , 'description' => 'Ungdomshøjen' ] ,
    [ 'active' => true  , 'place_nr' => '5' , 'area_id' => 'A' , 'place_owner_id' => null , 'description' => 'Højskolerns Folkekøkken' ] ,
    [ 'active' => true  , 'place_nr' => '6' , 'area_id' => 'A' , 'place_owner_id' => null , 'description' => 'Landdistrikternes Telt' ] ,
    [ 'active' => true  , 'place_nr' => '1' , 'area_id' => 'B' , 'place_owner_id' => null , 'description' => 'Advice Scenen' ] ,
    [ 'active' => false , 'place_nr' => '2' , 'area_id' => 'B' , 'place_owner_id' => null , 'description' => 'Klima & Energi Scenen' ] ,
    [ 'active' => true  , 'place_nr' => '3' , 'area_id' => 'B' , 'place_owner_id' => null , 'description' => 'Timm Vladimirs Køkken' ] ,
    [ 'active' => true  , 'place_nr' => '6' , 'area_id' => 'B' , 'place_owner_id' => null , 'description' => 'Klima & Energi Teltet' ] 
] ;

/*
 *  main
 */
 
foreach ( $places as $key => $place )
{
//     print_r( $place ) ;
//     $place['place_owner_id'] = (int) ( new PlaceOwner( 'name' , 'dummy' ) )->getData()['place_owner_id'] ;
    $place['area_id']        = (int) ( new Area( 'name'  , $place['area_id'] ) )->getData()['area_id'] ;
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
    $areaID = ( new Area( 'name' , $areaName ) )->getData()['area_id'] ;
    $placeownerID = ( new PlaceOwner( 'surname' , $username ) )->getData()['place_owner_id'] ;
    $place  = new Place( ['area_id','place_nr'] , [$areaID,$placeNr] ) ;
    $place->setValues(['place_owner_id' => $placeownerID]) ;
}



$allPlaces = new Places() ;
foreach ( $allPlaces->getPlaces() as $place ) 
    echo json_encode( $place->getData() , JSON_UNESCAPED_UNICODE ) . \PHP_EOL ;
    unset( $place ) ;

echo '</pre>' ;
?>

