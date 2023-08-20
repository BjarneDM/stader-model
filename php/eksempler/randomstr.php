<?php   namespace Stader\Eksempler ;

   $include_paths[] = dirname( __DIR__ ) ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/cdn/php' ;
// $include_paths[] =  '.' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/cdn/_/php' ;
set_include_path( implode( ':' , $include_paths ) ) ;

require_once( 'classloader.php' ) ;

use \Stader\Model\{RandomStr} ;

$randomStr = new RandomStr() ;
echo $randomStr->current() . \PHP_EOL ;
$randomStr = new RandomStr( length: 24, ks: 0 ) ;
echo $randomStr->current() . \PHP_EOL ;
echo $randomStr->next() . \PHP_EOL ;

?>
