<?php namespace Stader\Eksempler ;

   $include_paths[] = dirname( __DIR__ ) ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/cdn/php' ;
// $include_paths[] =  '.' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/cdn/_/php' ;
set_include_path( implode( ':' , $include_paths ) ) ;

require_once( 'settings/phpValues.php' ) ;

echo str_repeat('-', 50) . \PHP_EOL . '$alleBrugere = new Users() ;' . \PHP_EOL ;

require_once( 'classloader.php' ) ;

use \Stader\Control\Tables\User\{User,Users} ;


$allUsers = new Users() ;
// print_r( $allUsers ) ;
// $alleBrugere = new Users(, 'postnr' , '4220' ) ;
// $alleBrugere = new Users(, ['navn_for'] , ['Bjarne'] ) ;
// $alleBrugere = new Users(, ['postnr','navn_for'] , ['4220','Bjarne'] ) ;

echo 'totalt antal brugere ' . $allUsers->count() . \PHP_EOL ;

do { print_r( $allUsers->current()->getData() ) ;
} while ( $allUsers->next() !== false ) ;

for ( $i = 0 ; $i < $allUsers->count() ; $i++ ) 
    print_r( $allUsers->getUser( $i )->getData() ) ;

foreach ( $allUsers->getUsers() as $user )
    print_r( $user->getData() ) ;


echo str_repeat('-', 50) . \PHP_EOL . '$alleBrugere = new Groups() ;' . \PHP_EOL ;


use \Stader\Model\Tables\Group\{UGroup,UGroups} ;


$allGroups = new UGroups() ;
// print_r( $allGroups ) ;
// $alleBrugere = new Groups(, 'postnr' , '4220' ) ;
// $alleBrugere = new Groups(, ['navn_for'] , ['Bjarne'] ) ;
// $alleBrugere = new Groups(, ['postnr','navn_for'] , ['4220','Bjarne'] ) ;

echo 'totalt antal brugere ' . $allGroups->count() . \PHP_EOL ;

do { print_r( $allGroups->current()->getData() ) ;
} while ( $allGroups->next() !== false ) ;

for ( $i = 0 ; $i < $allGroups->count() ; $i++ ) 
    print_r( $allGroups->getGroup( $i )->getData() ) ;

foreach ( $allGroups->getGroups() as $group )
    print_r( $group->getData() ) ;


echo str_repeat('-', 50) . \PHP_EOL . '$alleBrugere = new Places() ;' . \PHP_EOL ;

use \Stader\Model\Tables\Place\{Place,Places} ;


$allPlaces = new Places() ;
// print_r( $allPlaces ) ;
// $alleBrugere = new Places(, 'postnr' , '4220' ) ;
// $alleBrugere = new Places(, ['navn_for'] , ['Bjarne'] ) ;
// $alleBrugere = new Places(, ['postnr','navn_for'] , ['4220','Bjarne'] ) ;

echo 'totalt antal brugere ' . $allPlaces->count() . \PHP_EOL ;

do { print_r( $allPlaces->current()->getData() ) ;
} while ( $allPlaces->next() !== false ) ;

for ( $i = 0 ; $i < $allPlaces->count() ; $i++ ) 
    print_r( $allPlaces->getPlace( $i )->getData() ) ;

foreach ( $allPlaces->getPlaces() as $place )
    print_r( $place->getData() ) ;


echo str_repeat('-', 50) . \PHP_EOL . '$alleBrugere = new Tickets() ;' . \PHP_EOL ;

use \Stader\Model\Tables\Ticket\{Ticket,Tickets} ;


$allTickets = new Tickets() ;
// print_r( $allTickets ) ;
// $alleBrugere = new Tickets(, 'postnr' , '4220' ) ;
// $alleBrugere = new Tickets(, ['navn_for'] , ['Bjarne'] ) ;
// $alleBrugere = new Tickets(, ['postnr','navn_for'] , ['4220','Bjarne'] ) ;

echo 'totalt antal brugere ' . $allTickets->count() . \PHP_EOL ;

do { print_r( $allTickets->current()->getData() ) ;
} while ( $allTickets->next() !== false ) ;

for ( $i = 0 ; $i < $allTickets->count() ; $i++ ) 
    print_r( $allTickets->getTicket( $i )->getData() ) ;

foreach ( $allTickets->getTickets() as $ticket )
    print_r( $ticket->getData() ) ;

?>
