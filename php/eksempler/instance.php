<?php namespace stader\eksempler ;

   $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/stader/php' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/cdn/php' ;
// $include_paths[] =  '.' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/cdn/_/php' ;
set_include_path( implode( ':' , $include_paths ) ) ;

// echo 'IncludePaths : ' . \PHP_EOL ;
// print_r( explode( ':' , get_include_path() ) ) ;

require_once( dirname( __file__ , 2 ) . '/control/class.classloader.php' ) ;
require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;

use \stader\model\{MInstance,MInstances} ;

$allowedClasses = MInstances::$allowedClasses ;
print_r( $allowedClasses ) ;


?>
