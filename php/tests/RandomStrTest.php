<?php   
declare(strict_types=1) ;
namespace Stader\Tests  ;

/*
 *  Usage :
 *      phpunit --cache-result-file=./phpunit.result.cache tests/RandomStrTest.php 
 */

    $include_paths   = [] ;
    $include_paths[] =  dirname( __DIR__ ) ;
set_include_path( implode( ':' , $include_paths ) ) ;

require_once( 'classloader.php' ) ;
require_once( 'vendor/autoload.php' ) ;

use \PHPUnit\Framework\TestCase;
use \Stader\Model\{RandomStr} ;

class RandomStrTest extends TestCase
{

    public function testGenerate1()
    {   echo \PHP_EOL . '-> entering ' . __function__ . \PHP_EOL ;
        $randomStr = new RandomStr() ;
        echo $randomStr->current() . \PHP_EOL ;
    }

    public function testGenerate2()
    {   echo \PHP_EOL . '-> entering ' . __function__ . \PHP_EOL ;
        $randomStr = new RandomStr( length: 24 , ks: 0 ) ;
        echo $randomStr->current() . \PHP_EOL ;
    }

}
?>
