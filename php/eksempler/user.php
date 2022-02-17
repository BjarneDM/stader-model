<?php namespace Stader\Eksempler ;

   $include_paths[] =   dirname( __DIR__ ) ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/cdn/php' ;
// $include_paths[] =  '.' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/cdn/_/php' ;
set_include_path( implode( ':' , $include_paths ) ) ;

// echo 'IncludePaths : ' . \PHP_EOL ;
// print_r( explode( ':' , get_include_path() ) ) ;

require_once( 'classloader.php' ) ;

use \Stader\Control\User\{User,Users} ;

$allowedKeys = User::$allowedKeys ;
print_r( $allowedKeys ) ;

/*
 *  test af ændring af en værdi 
 */
$users = new Users() ; $users->rewind() ; 
$user  = $users->current() ;
print_r( $user->getData() ) ;
$user->setValues( [ 'surname' => 'EfterNavn' ] ) ;
$id = $user->getData()['id'] ;
print_r( ( new User( $id ) )->getData() ) ;

/*
 *  test af læsning af eksisterende User på basis af forskellige værdier
 */
// kan ikke længere læse i userinfo efter krypteringen
// $user = new User( ['name'] , ['Kris'] ) ;
// print_r( $user->getData() ) ;
$user = new User( ['email'] , ['skp@example.com'] ) ;
print_r( $user->getData() ) ;
// ignorerer værdier i userinfo
$user = new User( ['email','name'] , ['skp@example.com','Kris'] ) ;
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
$testUser1 = new User( $newUser ) ;
print_r( $testUser1->getData() ) ;

echo str_repeat('-', 50) . \PHP_EOL . '$testUser1->setValues( [\'username\' => \'KKristensen\'] ) ;' . \PHP_EOL ;
$testUser1->setValues( ['username' => 'KKristensen'] ) ;
echo 'username : ' . $testUser1->getData()['username'] . PHP_EOL ;

echo str_repeat('-', 50) . \PHP_EOL . '$testUser1 = new User( \'username\' , \'KKristensen\' ) ;' . \PHP_EOL ;
$testUser1 = new User( 'username' , 'KKristensen' ) ;
$testUser1 = new User(  ['username'] , ['KKristensen'] ) ;

print_r( $testUser1->getKeys() ) ;
var_dump( $testUser1 ) ;

$testUser1->delete() ;
exit() ;

?>
