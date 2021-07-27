<?php   namespace stader\tests ;
/*
 *  Usage :
 *      phpunit --cache-result-file=./phpunit.result.cache tests/UserCreateTest.php 
 */
use PHPUnit\Framework\TestCase;

set_include_path( dirname( __file__ , 2 ) ) ;

/*

create table users
(
    id          int auto_increment primary key , <- denne bliver genereret af DB
    name        varchar(255) not null ,          <- de resterende felter er krævede
    surname     varchar(255) default '' ,
    phone       varchar(255) not null ,
    username    varchar(255) not null ,
        constraint unique (username) ,
    passwd      varchar(255) not null ,
    email       varchar(255) not null ,
        constraint unique (email) ,
) engine = memory ;

 */

require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;

use \stader\model\{User} ;

class UserCreateTest extends TestCase
{
    protected static $setup = null ;
    protected static $newUser = [] ;

    public static function setUpBeforeClass() : void
    {   echo '-> entering ' . __function__ . \PHP_EOL ;

        self::$newUser['name']      = 'Kris' ;
        self::$newUser['surname']   = 'Kristensen' ;
        self::$newUser['phone']     = '12 34 56 78' ;
        self::$newUser['username']  = 'KrisKris' ;
        self::$newUser['passwd']    = 'instruktør' ;
        self::$newUser['email']     = 'kkz@zbc.dk' ;

        echo '<- exiting ' . __function__ . \PHP_EOL ;
    }

    public function testConstruct1()
    {   echo \PHP_EOL . '-> entering ' . __function__ . \PHP_EOL ;
        /*
         *  helt ny user oprettes
         */
        $testUser = new User( self::$setup::$connect , self::$newUser ) ;
        $data = $testUser->getData() ;
        print_r( $data ) ;

        $this->assertIsObject( $testUser ) ;
        $this->assertEquals( 7 , count( $testUser->getData() ) ) ;

        $this->assertEquals( self::$newUser['name']         , $data['name'] ) ;
        $this->assertEquals( self::$newUser['surname']      , $data['surname'] ) ;
        $this->assertEquals( self::$newUser['phone']        , $data['phone'] ) ;
        $this->assertEquals( self::$newUser['username']     , $data['username'] ) ;
        $this->assertNotEquals( self::$newUser['passwd']    , $data['passwd'] ) ;
        $this->assertEquals( self::$newUser['email']        , $data['email'] ) ;

        echo '<- exiting ' . __function__ . \PHP_EOL ;
    return (int) $data['user_id'] ; }

    /**
     *  @depends testConstruct1
     */
    public function testConstruct2( int $user_id )
    {   echo \PHP_EOL . '-> entering ' . __function__ . \PHP_EOL ;
        /*
         *  user hentes fra databasen baseret på id
         */
        $testUser = new User( $user_id ) ;
        $data = $testUser->getData() ;

        $this->assertIsObject( $testUser ) ;
        $this->assertEquals( 7 , count( $testUser->getData() ) ) ;

        $this->assertEquals( $user_id                       , $data['id'] ) ;
        $this->assertEquals( self::$newUser['name']         , $data['name'] ) ;
        $this->assertEquals( self::$newUser['surname']      , $data['surname'] ) ;
        $this->assertEquals( self::$newUser['phone']        , $data['phone']  ) ;
        $this->assertEquals( self::$newUser['username']     , $data['username'] ) ;
        $this->assertNotEquals( self::$newUser['passwd']    , $data['passwd'] ) ;
        $this->assertEquals( self::$newUser['email']        , $data['email'] ) ;

        echo '<- exiting ' . __function__ . \PHP_EOL ;
    return $testUser ; }

    /**
     *  @depends testConstruct1
     */
    public function testConstruct3( int $user_id )
    {   echo \PHP_EOL . '-> entering ' . __function__ . \PHP_EOL ;
        /*
         *  user hentes fra databasen baseret på 'username' som string
         */
        $testUser = new User( 'username' , 'KrisKris' ) ;
        $data = $testUser->getData() ;

        $this->assertIsObject( $testUser ) ;
        $this->assertEquals( 7 , count( $testUser->getData() ) ) ;

        $this->assertEquals( $user_id                       , $data['user_id'] ) ;
        $this->assertEquals( self::$newUser['name']         , $data['name'] ) ;
        $this->assertEquals( self::$newUser['surname']      , $data['surname'] ) ;
        $this->assertEquals( self::$newUser['phone']        , $data['phone']  ) ;
        $this->assertEquals( self::$newUser['username']     , $data['username'] ) ;
        $this->assertNotEquals( self::$newUser['passwd']    , $data['passwd'] ) ;
        $this->assertEquals( self::$newUser['email']        , $data['email'] ) ;

        echo '<- exiting ' . __function__ . \PHP_EOL ;
    }

    /**
     *  @depends testConstruct1
     */
    public function testConstruct4( int $user_id )
    {   echo \PHP_EOL . '-> entering ' . __function__ . \PHP_EOL ;
        /*
         *  gruppe hentes fra databasen baseret på 'username' som Array
         */
        $testUser = new User( ['username'] , ['KrisKris'] ) ;
        $data = $testUser->getData() ;

        $this->assertIsObject( $testUser ) ;
        $this->assertEquals( 7 , count( $testUser->getData() ) ) ;

        $this->assertEquals( $user_id                       , $data['user_id'] ) ;
        $this->assertEquals( self::$newUser['name']         , $data['name'] ) ;
        $this->assertEquals( self::$newUser['surname']      , $data['surname'] ) ;
        $this->assertEquals( self::$newUser['phone']        , $data['phone']  ) ;
        $this->assertEquals( self::$newUser['username']     , $data['username'] ) ;
        $this->assertNotEquals( self::$newUser['passwd']    , $data['passwd'] ) ;
        $this->assertEquals( self::$newUser['email']        , $data['email'] ) ;

        echo '<- exiting ' . __function__ . \PHP_EOL ;
    }

    /**
     *  @depends testConstruct1
     */
    public function testConstruct5( int $user_id )
    {   echo \PHP_EOL . '-> entering ' . __function__ . \PHP_EOL ;
        /*
         *  gruppe hentes fra databasen baseret på ['username','email'] som Array
         */
        $testUser = new User( ['username','email'] , ['KrisKris','kkz@zbc.dk'] ) ;
        $data = $testUser->getData() ;

        $this->assertIsObject( $testUser ) ;
        $this->assertEquals( 7 , count( $testUser->getData() ) ) ;

        $this->assertEquals( $user_id                       , $data['user_id'] ) ;
        $this->assertEquals( self::$newUser['name']         , $data['name'] ) ;
        $this->assertEquals( self::$newUser['surname']      , $data['surname'] ) ;
        $this->assertEquals( self::$newUser['phone']        , $data['phone']  ) ;
        $this->assertEquals( self::$newUser['username']     , $data['username'] ) ;
        $this->assertNotEquals( self::$newUser['passwd']    , $data['passwd'] ) ;
        $this->assertEquals( self::$newUser['email']        , $data['email'] ) ;

        echo '<- exiting ' . __function__ . \PHP_EOL ;
    }

    /**
     *  @depends testConstruct2
     */
    public function testDelete( User $testUser )
    {   echo \PHP_EOL . '-> entering ' . __function__ . \PHP_EOL ;
        $rowCount = $testUser->delete() ;

        $this->assertEquals( 1 , $rowCount ) ;
        $this->assertEmpty( $testUser->getData() ) ;

        echo '<- exiting ' . __function__ . \PHP_EOL ;
    }

    public static function tearDownAfterClass() : void
    {   echo '-> entering ' . __function__ . \PHP_EOL ;
        self::$setup = null ;

        echo '<- exiting ' . __function__ . \PHP_EOL ;
    }
    
}

?>
