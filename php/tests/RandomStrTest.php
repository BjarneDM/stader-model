<?php   namespace stader\tests ;
/*
 *  Usage :
 *      phpunit --cache-result-file=./phpunit.result.cache tests/RandomStrTest.php 
 */
use PHPUnit\Framework\TestCase;

set_include_path( '/Volumes/Bjarne/Sites/info/mathiesen/zbc/stader/php' ) ;

require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;

use \stader\model\{RandomStr} ;

class RandomStrTest extends TestCase
{

    public function testGenerate1()
    {   echo \PHP_EOL . '-> entering ' . __function__ . \PHP_EOL ;
        $randomStr = new RandomStr() ;
        echo $randomStr->current() . \PHP_EOL ;
    }

    public function testGenerate2()
    {   echo \PHP_EOL . '-> entering ' . __function__ . \PHP_EOL ;
        $randomStr = new RandomStr( [ 'length' => 24 , 'ks' => 0 ] ) ;
        echo $randomStr->current() . \PHP_EOL ;
    }

}
?>
