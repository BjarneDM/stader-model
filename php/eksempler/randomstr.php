<?php   namespace stader\tests ;
set_include_path( '/Volumes/Bjarne/Sites/info/mathiesen/zbc/stader/php' ) ;

require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;

use \stader\model\{RandomStr} ;

        $randomStr = new RandomStr() ;
        echo $randomStr->current() . \PHP_EOL ;
        $randomStr = new RandomStr( [ 'length' => 24 , 'ks' => 0 ] ) ;
        echo $randomStr->current() . \PHP_EOL ;
        echo $randomStr->next() . \PHP_EOL ;

?>
