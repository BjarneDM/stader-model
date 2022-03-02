<?php   
declare(strict_types=1) ;
namespace Stader\Tests  ;

    $include_paths   = [] ;
    $include_paths[] =  dirname( __DIR__ ) ;
set_include_path( implode( ':' , $include_paths ) ) ;

require_once( 'classloader.php' ) ;
require_once( 'vendor/autoload.php' ) ;

/*
 *  Usage :
 *      phpunit --cache-result-file=./phpunit.result.cache tests/CreateGroupTest.php 
 */
use PHPUnit\Framework\TestCase;


/*

create table groups
(
    id      int auto_increment primary key ,
    name    varchar(255) not null ,
        constraint unique (name) ,
    description varchar(255)
) ;

 */

use \Stader\Model\Tables\Group\{UGroup} ;

class GroupCreateTest extends TestCase
{
    protected static $setup = null ;
    protected static $newGroup = [] ;

    public static function setUpBeforeClass() : void
    {   echo '-> entering ' . __function__ . \PHP_EOL ;

        self::$newGroup['name']         = 'xxxTransportxxx' ;
        self::$newGroup['description']  = 'xxxSørger for at udstyr køres fra base til stadepladsxxx' ;

        echo '<- exiting ' . __function__ . \PHP_EOL ;
    }

    public function testConstruct1()
    {   echo \PHP_EOL . '-> entering ' . __function__ . \PHP_EOL ;
        /*
         *  helt ny gruppe oprettes
         */
        $testGroup = new UGroup( self::$newGroup ) ;
        $data = $testGroup->getData() ;
        print_r( $data ) ;

        $this->assertIsObject( $testGroup ) ;
        $this->assertEquals( 3 , count( $testGroup->getData() ) ) ;

        $this->assertEquals( self::$newGroup['name']        , $data['name']    ) ;
        $this->assertEquals( self::$newGroup['description'] , $data['description'] ) ;

        echo '<- exiting ' . __function__ . \PHP_EOL ;
    return (int) $data['id'] ; }

    /**
     *  @depends testConstruct1
     */
    public function testConstruct2( int $group_id )
    {   echo \PHP_EOL . '-> entering ' . __function__ . \PHP_EOL ;
        /*
         *  gruppe hentes fra databasen baseret på gruppe_id
         */
        var_dump( $group_id ) ;
        $testGroup = new UGroup( $group_id ) ;
        $data = $testGroup->getData() ;
        print_r( $data ) ;

        $this->assertIsObject( $testGroup ) ;
        $this->assertEquals( 3 , count( $testGroup->getData() ) ) ;

        $this->assertEquals( $group_id                      , $data['id']    ) ;
        $this->assertEquals( self::$newGroup['name']        , $data['name']    ) ;
        $this->assertEquals( self::$newGroup['description'] , $data['description'] ) ;

        echo '<- exiting ' . __function__ . \PHP_EOL ;
    return $testGroup ; }

    /**
     *  @depends testConstruct1
     */
    public function testConstruct3( int $group_id )
    {   echo \PHP_EOL . '-> entering ' . __function__ . \PHP_EOL ;
        /*
         *  gruppe hentes fra databasen baseret på 'name' som string
         */
        $testGroup = new UGroup( 'name' , self::$newGroup['name'] ) ;
        $data = $testGroup->getData() ;
        print_r( $data ) ;

        $this->assertIsObject( $testGroup ) ;
        $this->assertEquals( 3 , count( $testGroup->getData() ) ) ;

        $this->assertEquals( $group_id                      , $data['id']    ) ;
        $this->assertEquals( self::$newGroup['name']        , $data['name']    ) ;
        $this->assertEquals( self::$newGroup['description'] , $data['description'] ) ;

        echo '<- exiting ' . __function__ . \PHP_EOL ;
    }

     /**
     *  @depends testConstruct1
     */
    public function testConstruct4( int $group_id )
    {   echo \PHP_EOL . '-> entering ' . __function__ . \PHP_EOL ;
        /*
         *  gruppe hentes fra databasen baseret på 'name' som Array
         */
        $testGroup = new UGroup( ['name'] , [self::$newGroup['name']] ) ;
        $data = $testGroup->getData() ;
        print_r( $data ) ;

        $this->assertIsObject( $testGroup ) ;
        $this->assertEquals( 3 , count( $testGroup->getData() ) ) ;

        $this->assertEquals( $group_id                      , $data['id']    ) ;
        $this->assertEquals( self::$newGroup['name']        , $data['name']    ) ;
        $this->assertEquals( self::$newGroup['description'] , $data['description'] ) ;

        echo '<- exiting ' . __function__ . \PHP_EOL ;
    }

   /**
     *  @depends testConstruct2
     */
    public function testDelete( UGroup $testGroup )
    {   echo \PHP_EOL . '-> entering ' . __function__ . \PHP_EOL ;
        $rowCount = $testGroup->delete() ;

        $this->assertEquals( 1 , $rowCount ) ;
        $this->assertEmpty( $testGroup->getData() ) ;

        echo '<- exiting ' . __function__ . \PHP_EOL ;
    }

    public static function tearDownAfterClass() : void
    {   echo '-> entering ' . __function__ . \PHP_EOL ;
        self::$setup = null ;

        echo '<- exiting ' . __function__ . \PHP_EOL ;
    }
    
}

?>
