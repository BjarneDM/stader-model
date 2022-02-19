<?php   namespace Stader\Eksempler ;
set_include_path( '/Volumes/Bjarne/Sites/info/mathiesen/zbc/stader/php' ) ;

require_once( 'classloader.php' ) ;

use \Stader\Model\{RandomStr} ;

        $randomStr = new RandomStr() ;
        echo $randomStr->current() . \PHP_EOL ;
        $randomStr = new RandomStr( [ 'length' => 24 , 'ks' => 0 ] ) ;
        echo $randomStr->current() . \PHP_EOL ;
        echo $randomStr->next() . \PHP_EOL ;

?>
