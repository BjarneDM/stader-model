<?php namespace Stader\Eksempler ;

   $include_paths[] = dirname( __DIR__ ) ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/cdn/php' ;
// $include_paths[] =  '.' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/cdn/_/php' ;
set_include_path( implode( ':' , $include_paths ) ) ;

require_once( 'settings/phpValues.php' ) ;
require_once( 'classloader.php' ) ;

echo str_repeat('-', 50) . \PHP_EOL . '$alleBrugere = new Users() ;' . \PHP_EOL ;
use \Stader\Control\User\{User,Users} ;

$allUsers = new Users() ;

echo 'totalt antal brugere ' . $allUsers->count() . \PHP_EOL ;

foreach ( $allUsers as $user )
    print_r( $user->getData() ) ;

foreach ( $allUsers->getIDs() as $ID )
    print_r( ( new User( $ID) )->getData() ) ;

echo str_repeat('-', 50) . \PHP_EOL . '$alleBrugere = new Groups() ;' . \PHP_EOL ;
use \Stader\Model\Tables\Group\{UGroup,UGroups} ;

$allGroups = new UGroups() ;

echo 'totalt antal grupper ' . $allGroups->count() . \PHP_EOL ;

foreach ( $allGroups as $group )
    print_r( $group->getData() ) ;

echo str_repeat('-', 50) . \PHP_EOL . '$alleBrugere = new Places() ;' . \PHP_EOL ;
use \Stader\Model\Tables\Place\{Place,Places} ;

$allPlaces = new Places() ;

echo 'totalt antal områder ' . $allPlaces->count() . \PHP_EOL ;

foreach ( $allPlaces as $place )
    print_r( $place->getData() ) ;


echo str_repeat('-', 50) . \PHP_EOL . '$alleBrugere = new Tickets() ;' . \PHP_EOL ;

use \Stader\Model\Tables\Ticket\{Ticket,Tickets} ;
$allTickets = new Tickets() ;

echo 'totalt antal tickets ' . $allTickets->count() . \PHP_EOL ;

foreach ( $allTickets as $ticket )
    print_r( $ticket->getData() ) ;

?>
