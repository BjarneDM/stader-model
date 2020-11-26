<?php namespace stader\eksempler ;

   $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/stader/php' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/cdn/php' ;
// $include_paths[] =  '.' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/cdn/_/php' ;
set_include_path( implode( ':' , $include_paths ) ) ;

require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;

use \stader\model\{User,Users} ;


echo str_repeat('-', 50) . \PHP_EOL . '$alleBrugere = new Users( $setup::$connect ) ;' . \PHP_EOL ;
$allUsers = new Users( $setup::$connect ) ;
print_r( $allUsers ) ;
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

print_r( $allUsers ) ;

?>
