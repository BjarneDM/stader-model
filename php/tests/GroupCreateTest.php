<?php   namespace stader\tests ;
/*
 *  Usage :
 *      phpunit --cache-result-file=./phpunit.result.cache tests/CreateGroupTest.php 
 */
use PHPUnit\Framework\TestCase;

set_include_path(  ) ;

/*

create table groups
(
    id      int auto_increment primary key ,
    name    varchar(255) not null ,
        constraint unique (name) ,
    description varchar(255)
) ;

 */

require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;

use \stader\model\{Group} ;

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
        $testGroup = new Group( self::$setup::$connect , self::$newGroup ) ;
        $data = $testGroup->getData() ;
        print_r( $data ) ;

        $this->assertIsObject( $testGroup ) ;
        $this->assertEquals( 3 , count( $testGroup->getData() ) ) ;

        $this->assertEquals( self::$newGroup['name']        , $data['name']    ) ;
        $this->assertEquals( self::$newGroup['description'] , $data['description'] ) ;

        echo '<- exiting ' . __function__ . \PHP_EOL ;
    return (int) $data['group_id'] ; }

    /**
     *  @depends testConstruct1
     */
    public function testConstruct2( int $group_id )
    {   echo \PHP_EOL . '-> entering ' . __function__ . \PHP_EOL ;
        /*
         *  gruppe hentes fra databasen baseret på gruppe_id
         */
        var_dump( $group_id ) ;
        $testGroup = new Group( self::$setup::$connect , $group_id ) ;
        $data = $testGroup->getData() ;
        print_r( $data ) ;

        $this->assertIsObject( $testGroup ) ;
        $this->assertEquals( 3 , count( $testGroup->getData() ) ) ;

        $this->assertEquals( $group_id                      , $data['group_id']    ) ;
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
        $testGroup = new Group( self::$setup::$connect , 'name' , self::$newGroup['name'] ) ;
        $data = $testGroup->getData() ;
        print_r( $data ) ;

        $this->assertIsObject( $testGroup ) ;
        $this->assertEquals( 3 , count( $testGroup->getData() ) ) ;

        $this->assertEquals( $group_id                      , $data['group_id']    ) ;
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
        $testGroup = new Group( self::$setup::$connect , ['name'] , [self::$newGroup['name']] ) ;
        $data = $testGroup->getData() ;
        print_r( $data ) ;

        $this->assertIsObject( $testGroup ) ;
        $this->assertEquals( 3 , count( $testGroup->getData() ) ) ;

        $this->assertEquals( $group_id                      , $data['group_id']    ) ;
        $this->assertEquals( self::$newGroup['name']        , $data['name']    ) ;
        $this->assertEquals( self::$newGroup['description'] , $data['description'] ) ;

        echo '<- exiting ' . __function__ . \PHP_EOL ;
    }

   /**
     *  @depends testConstruct2
     */
    public function testDelete( Group $testGroup )
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
