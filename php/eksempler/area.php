<?php namespace Stader\Eksempler ;

   $include_paths[] = dirname( __DIR__ ) ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/cdn/php' ;
// $include_paths[] =  '.' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/cdn/_/php' ;
set_include_path( implode( ':' , $include_paths ) ) ;

// echo 'IncludePaths : ' . \PHP_EOL ;
// print_r( explode( ':' , get_include_path() ) ) ;

require_once( 'classloader.php' ) ;

use \Stader\Model\Tables\Area\{Area,Areas} ;

foreach ( [ 64 , 96 ] as $i )
{   for ( $j=1 ; $j<27 ; $j++ )
    {
        try {
            print_r( ( new Area( 'name' , chr( $i+$j ) ) )->getData() ) ;
        } catch ( \Exception $e) {}
    }
}

// $alleOmråder = new Areas() ;
// print_r( $alleOmråder->getOne(0)->delete() ) ;

$testOmråde = new Area( [ 'name' => 'M' , 'description' => 'Mike\'s Område' ] ) ;
print_r( $testOmråde->delete() ) ;

exit() ;

?>
