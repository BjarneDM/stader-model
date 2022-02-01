<?php   namespace Stader\Bootstrap ;

echo '<pre>' ;
echo \PHP_EOL . str_repeat( '-' , 50 ) . \PHP_EOL . '-> entering : ' . basename( __file__ ) . \PHP_EOL ;



/*

create table if not exists place_owner
(
    place_owner_id  int auto_increment primary key ,
    name            varchar(255) not null ,
    surname         varchar(255) not null ,
    phone           varchar(255) not null ,
    email           varchar(255) not null ,
    organisation    varchar(255) not null
) ;

 */


/*
 *  setup
 */

require_once( dirname( __dir__ ) . '/classloader.php' ) ;
use \Stader\Model\Tables\PlaceOwner\{PlaceOwner,PlaceOwners} ;
use \Stader\Model\{RandomStr} ;

/*
 *  data
 */

//     [ 'name' => '' , 'surname' => '' , 'phone' => '' , 'email' => '' , 'organisation' => '' ] ,
//     [ 'name' => '' , 'surname' => '' , 'phone' => '' , 'username' => '' , 'passwd' => $passwords[$i++ % count( $passwords )] , 'email' => '' ] ,
// $users = new Users() ;

//     [ 'name' => '' , 'surname' => '' , 'phone' => '' , 'organisation' => '' ,'email' => '' ] ,
$users =
[
    [ 'name' => 'Michael' , 'surname' => 'Rasmussen' , 'phone' => 'D' , 'organisation' => 'ZBC Slagelse' ,'email' => '' ] ,
    [ 'name' => 'Lars' , 'surname' => 'Starbech' , 'phone' => 'B' , 'organisation' => 'Schmidts Radio' ,'email' => '' ] ,
    [ 'name' => 'SKP-IT' , 'surname' => 'Slagelse' , 'phone' => 'A' , 'organisation' => 'SKP Slagelse' ,'email' => '' ] ,
    [ 'name' => 'Lars' , 'surname' => 'Nielsen' , 'phone' => 'S' , 'organisation' => 'Schmidts Radio' ,'email' => '' ] ,
    [ 'name' => 'Kris' , 'surname' => 'Kristensen' , 'phone' => 'C' , 'organisation' => 'Kris Kringlefabrik' ,'email' => '' ] 

//     [ 'name' => 'Casper' , 'surname' => 'Andersen' , 'phone' => 'D' , 'organisation' => 'casp7654' ,'email' => '1' ] ,
//     [ 'name' => 'Toke' , 'surname' => 'Juholke Kejlow Nielsen' , 'phone' => 'E' , 'organisation' => 'toke1254' ,'email' => '4' ] 
//     [ 'name' => 'Mike' , 'surname' => 'Kyed Jespersen' , 'phone' => 'G' , 'organisation' => 'mike4098' ,'email' => '3' ] ,
//     [ 'name' => 'Alexander' , 'surname' => 'Lackovic Hansen' , 'phone' => 'H' , 'organisation' => 'alex303a' ,'email' => '5' ] ,
//     [ 'name' => 'Bjarne' , 'surname' => 'Mathiesen' , 'phone' => 'J' , 'organisation' => 'bjar9215' ,'email' => '0' ] ,

//     [ 'name' => 'Adam' , 'surname' => 'Tayeb Bachir' , 'phone' => '' , 'organisation' => 'zbcadbac1' ,'email' => '6' ] ,
//     [ 'name' => 'Anders' , 'surname' => 'Agerlund' , 'phone' => '' , 'organisation' => 'ande319i' ,'email' => '7' ] ,
//     [ 'name' => 'Andi' , 'surname' => 'Olle Olsen' , 'phone' => '' , 'organisation' => 'zbcanols21' ,'email' => '8' ] ,
//     [ 'name' => 'Benjamin' , 'surname' => 'Heltborg Roesdal' , 'phone' => '' , 'organisation' => 'benj6414' ,'email' => '9' ]
] ;


/*
 *  main
 */

( new PlaceOwners() )->deleteAll() ;

// foreach ( $users->getUsers() as $key => $user )
// {
//     $data = $user->getData() ;
//     $data['organisation'] = $data['username'] ;
//     unset( $data['user_id'] , $data['username'] , $data['passwd'] ) ;
//     $placeowner = new PlaceOwner( $data ) ;
// }   unset( $key , $user ) ;

$phone = new RandomStr( [ 'length' => 4 , 'ks' => 4 ] ) ;
foreach ( $users as $key => $user )
{
    $user['email'] = str_replace( ' ' , '-' , strtolower( "{$user['name']}@{$user['organisation']}.dk" ) ) ;
    $user['phone'] = $phone->next() . ' ' . $phone->next() ;
    $placeowner = new PlaceOwner( $user ) ;
}

foreach ( ( new PlaceOwners() ) as $placeowner )
    echo json_encode( $placeowner->getData() , JSON_UNESCAPED_UNICODE ) . \PHP_EOL ;
    unset( $placeowner , $allPlaceOwners ) ;

echo '</pre>' ;
?>
