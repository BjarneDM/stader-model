<?php namespace stader\eksempler ;

   $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/stader/php' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/cdn/php' ;
// $include_paths[] =  '.' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/cdn/_/php' ;
set_include_path( implode( ':' , $include_paths ) ) ;

require_once( 'settings/phpValues.php' ) ;

echo str_repeat('-', 50) . \PHP_EOL . '$alleBrugere = new Users( $setup::$connect ) ;' . \PHP_EOL ;

require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;

use \stader\model\{User,Users} ;


$allUsers = new Users( $setup::$connect ) ;
// print_r( $allUsers ) ;
// $alleBrugere = new Users( $setup::$connect , 'postnr' , '4220' ) ;
// $alleBrugere = new Users( $setup::$connect , ['navn_for'] , ['Bjarne'] ) ;
// $alleBrugere = new Users( $setup::$connect , ['postnr','navn_for'] , ['4220','Bjarne'] ) ;

echo 'totalt antal brugere ' . $allUsers->count() . \PHP_EOL ;

do { print_r( $allUsers->current()->getData() ) ;
} while ( $allUsers->next() !== false ) ;

for ( $i = 0 ; $i < $allUsers->count() ; $i++ ) 
    print_r( $allUsers->getUser( $i )->getData() ) ;

foreach ( $allUsers->getUsers() as $user )
    print_r( $user->getData() ) ;


echo str_repeat('-', 50) . \PHP_EOL . '$alleBrugere = new Groups( $setup::$connect ) ;' . \PHP_EOL ;

require_once( 'model/class.setup.php' ) ;
require_once( 'model/group/class.group.php' ) ;
require_once( 'model/group/class.groups.php' ) ;

use \stader\model\{Group,Groups} ;


$allGroups = new Groups( $setup::$connect ) ;
// print_r( $allGroups ) ;
// $alleBrugere = new Groups( $setup::$connect , 'postnr' , '4220' ) ;
// $alleBrugere = new Groups( $setup::$connect , ['navn_for'] , ['Bjarne'] ) ;
// $alleBrugere = new Groups( $setup::$connect , ['postnr','navn_for'] , ['4220','Bjarne'] ) ;

echo 'totalt antal brugere ' . $allGroups->count() . \PHP_EOL ;

do { print_r( $allGroups->current()->getData() ) ;
} while ( $allGroups->next() !== false ) ;

for ( $i = 0 ; $i < $allGroups->count() ; $i++ ) 
    print_r( $allGroups->getGroup( $i )->getData() ) ;

foreach ( $allGroups->getGroups() as $group )
    print_r( $group->getData() ) ;


echo str_repeat('-', 50) . \PHP_EOL . '$alleBrugere = new Places( $setup::$connect ) ;' . \PHP_EOL ;

require_once( 'model/class.setup.php' ) ;
require_once( 'model/place/class.place.php' ) ;
require_once( 'model/place/class.places.php' ) ;

use \stader\model\{Placwe,Places} ;


$allPlaces = new Places( $setup::$connect ) ;
// print_r( $allPlaces ) ;
// $alleBrugere = new Places( $setup::$connect , 'postnr' , '4220' ) ;
// $alleBrugere = new Places( $setup::$connect , ['navn_for'] , ['Bjarne'] ) ;
// $alleBrugere = new Places( $setup::$connect , ['postnr','navn_for'] , ['4220','Bjarne'] ) ;

echo 'totalt antal brugere ' . $allPlaces->count() . \PHP_EOL ;

do { print_r( $allPlaces->current()->getData() ) ;
} while ( $allPlaces->next() !== false ) ;

for ( $i = 0 ; $i < $allPlaces->count() ; $i++ ) 
    print_r( $allPlaces->getPlacwe( $i )->getData() ) ;

foreach ( $allPlaces->getPlaces() as $place )
    print_r( $place->getData() ) ;


echo str_repeat('-', 50) . \PHP_EOL . '$alleBrugere = new Tickets( $setup::$connect ) ;' . \PHP_EOL ;

require_once( 'model/class.setup.php' ) ;
require_once( 'model/ticket/class.ticket.php' ) ;
require_once( 'model/ticket/class.tickets.php' ) ;

use \stader\model\{Ticket,Tickets} ;


$allTickets = new Tickets( $setup::$connect ) ;
// print_r( $allTickets ) ;
// $alleBrugere = new Tickets( $setup::$connect , 'postnr' , '4220' ) ;
// $alleBrugere = new Tickets( $setup::$connect , ['navn_for'] , ['Bjarne'] ) ;
// $alleBrugere = new Tickets( $setup::$connect , ['postnr','navn_for'] , ['4220','Bjarne'] ) ;

echo 'totalt antal brugere ' . $allTickets->count() . \PHP_EOL ;

do { print_r( $allTickets->current()->getData() ) ;
} while ( $allTickets->next() !== false ) ;

for ( $i = 0 ; $i < $allTickets->count() ; $i++ ) 
    print_r( $allTickets->getTicket( $i )->getData() ) ;

foreach ( $allTickets->getTickets() as $ticket )
    print_r( $ticket->getData() ) ;

?>
