<?php namespace stader\eksempler ;

   $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/stader/php' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/cdn/php' ;
// $include_paths[] =  '.' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/cdn/_/php' ;
set_include_path( implode( ':' , $include_paths ) ) ;

// echo 'IncludePaths : ' . \PHP_EOL ;
// print_r( explode( ':' , get_include_path() ) ) ;

require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;

use \stader\model\{Group,Groups} ;


$newGroup = [] ;
$newGroup['name']         = 'Transport' ;
$newGroup['description']  = 'Sørger for at udstyr køres fra base til stadeplads' ;

echo str_repeat('-', 50) . \PHP_EOL . '$testGroup = new Group( $setup::$connect , $newGroup ) ;' . \PHP_EOL ;
$testGroup = new Group( $setup::$connect , $newGroup ) ;
print_r( $testGroup->getData() ) ;

exit() ;

?>
