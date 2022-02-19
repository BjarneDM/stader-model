<?php namespace Stader\Eksempler ;

   $include_paths[] = dirname( __DIR__ ) ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/cdn/php' ;
// $include_paths[] =  '.' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/cdn/_/php' ;
set_include_path( implode( ':' , $include_paths ) ) ;

// echo 'IncludePaths : ' . \PHP_EOL ;
// print_r( explode( ':' , get_include_path() ) ) ;

require_once( 'classloader.php' ) ;

use \Stader\Model\Tables\Group\{UGroup,UGroups} ;


$newGroup = [] ;
$newGroup['name']         = 'Transport' ;
$newGroup['description']  = 'Sørger for at udstyr køres fra base til stadeplads' ;

echo str_repeat('-', 50) . \PHP_EOL . '$testGroup = new Group( $newGroup ) ;' . \PHP_EOL ;
$testGroup = new UGroup( $newGroup ) ;
print_r( $testGroup->getData() ) ;

exit() ;

?>
