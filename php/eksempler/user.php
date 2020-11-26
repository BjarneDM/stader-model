<?php namespace stader\eksempler ;

   $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/stader/php' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/cdn/php' ;
// $include_paths[] =  '.' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/cdn/_/php' ;
set_include_path( implode( ':' , $include_paths ) ) ;

// echo 'IncludePaths : ' . \PHP_EOL ;
// print_r( explode( ':' , get_include_path() ) ) ;

require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;

use \stader\model\{User,Users} ;

$allowedKeys = User::$allowedKeys ;
print_r( $allowedKeys ) ;

$user = ( new Users() )->getUser(0) ;
print_r( $user->getData() ) ;
$user->setValues( [ 'surname' => 'EfterNavn' ] ) ;
print_r( $user->getData() ) ;

exit() ;


$newUser = [] ;
$newUser['name']      = 'Kris' ;
$newUser['surname']   = 'Kristensen' ;
$newUser['phone']     = '12 34 56 78' ;
$newUser['username']  = 'KrisKris' ;
$newUser['passwd']    = 'instruktør' ;
$newUser['email']     = 'kkz@zbc.ck' ;

echo str_repeat('-', 50) . \PHP_EOL . '$testUser1 = new User( $newUser ) ;' . \PHP_EOL ;
$testUser1 = new User( $setup::$connect , $newUser ) ;
print_r( $testUser1->getData() ) ;

echo str_repeat('-', 50) . \PHP_EOL . '$testUser1->setValues( [\'username\' => \'KKristensen\'] ) ;' . \PHP_EOL ;
$testUser1->setValues( ['username' => 'KKristensen'] ) ;
echo 'username : ' . $testUser1->getData()['username'] . PHP_EOL ;

echo str_repeat('-', 50) . \PHP_EOL . '$testUser1 = new User( $setup::$connect , \'username\' , \'KKristensen\' ) ;' . \PHP_EOL ;
$testUser1 = new User( $setup::$connect , 'username' , 'KKristensen' ) ;
$testUser1 = new User( $setup::$connect , ['username'] , ['KKristensen'] ) ;

print_r( $testUser1->getKeys() ) ;
var_dump( $testUser1 ) ;

$testUser1->delete() ;
exit() ;

?>
