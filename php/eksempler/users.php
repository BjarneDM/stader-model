<?php namespace Stader\Eksempler ;

   $include_paths[] =  dirname( __DIR__ ) ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/cdn/php' ;
// $include_paths[] =  '.' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/cdn/_/php' ;
set_include_path( implode( ':' , $include_paths ) ) ;

require_once( 'classloader.php' ) ;

use \Stader\Control\User\{User,Users} ;

echo str_repeat('-', 50) . \PHP_EOL . '$allUsers = new Users() ;' . \PHP_EOL ;
$allUsers = new Users() ;
print_r( $allUsers ) ;
// $allUsers = new Users('postnr' , '4220' ) ;
// $allUsers = new Users(['navn_for'] , ['Bjarne'] ) ;
// $allUsers = new Users(['postnr','navn_for'] , ['4220','Bjarne'] ) ;

echo 'totalt antal brugere ' . $allUsers->count() . \PHP_EOL ;

do { 
    print_r( $allUsers->current()->getData() ) ;
    $allUsers->next() ;
} while ( $allUsers->valid() ) ;

foreach ( $allUsers as $user )
    print_r( $user->getData() ) ;

print_r( $allUsers ) ;

?>
