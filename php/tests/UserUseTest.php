<?php   namespace stader\tests ;
use PHPUnit\Framework\TestCase;

set_include_path( '/Volumes/Bjarne/Sites/info/mathiesen/zbc/stader/php' ) ;

require_once( 'classloader.php' ) ;

use \Stader\Model\Tables\\{User} ;

class UserUseTest extends TestCase
{
    protected static $setup = null ;
    protected static $newUser = [] ;
    protected $testUser = null ;

    public static function setUpBeforeClass() : void
    {   echo \PHP_EOL . '-> entering ' . __function__ . \PHP_EOL ;

        self::$newUser['name']      = 'Kris' ;
        self::$newUser['surname']   = 'Kristensen' ;
        self::$newUser['phone']     = '12 34 56 78' ;
        self::$newUser['username']  = 'KrisKris' ;
        self::$newUser['passwd']    = 'instruktør' ;
        self::$newUser['email']     = 'kkz@zbc.dk' ;
    }

    protected function setUp() : void
    {   echo \PHP_EOL . '-> entering ' . __function__ . \PHP_EOL ;
        $this->testUser = new User( self::$setup::$connect , self::$newUser ) ;
    }


    /**
     */
//     public function testGetData()
//     {   echo \PHP_EOL . '-> entering ' . __function__ . \PHP_EOL ;
//         $this->assertTrue( true ) ;
// 
//         echo '<- exiting ' . __function__ . \PHP_EOL ;
//     }

    /**
     */
//     public function testGetKeyes()
//     {   echo \PHP_EOL . '-> entering ' . __function__ . \PHP_EOL ;
//         $this->assertTrue( true ) ;
// 
//         echo '<- exiting ' . __function__ . \PHP_EOL ;
//     }

    /**
     */
//     public function testGetValues()
//     {   echo \PHP_EOL . '-> entering ' . __function__ . \PHP_EOL ;
//         $this->assertTrue( true ) ;
// 
//         echo '<- exiting ' . __function__ . \PHP_EOL ;
//     }

    /**
     */
    public function testSetValues()
    {   echo \PHP_EOL . '-> entering ' . __function__ . \PHP_EOL ;

        print_r( $this->testUser->getData() ) ;
        $this->testUser->setValues( [ 'name' => 'Bjarne' , 'surname' => 'Mathiesen' ] ) ;
        $this->testUser->setValues( [ 'passwd' => 'BjarneMathiesen' ] ) ;
        print_r( $this->testUser->getData() ) ;
        $this->assertTrue( true ) ;

        echo '<- exiting ' . __function__ . \PHP_EOL ;
    }

    protected function tearDown() : void
    {   // echo '-> entering ' . __function__ . \PHP_EOL ;
        $this->testUser->delete() ;

        echo '<- exiting ' . __function__ . \PHP_EOL ;
    }

    public static function tearDownAfterClass() : void
    {   // echo '-> entering ' . __function__ . \PHP_EOL ;
        self::$setup = null ;

        echo '<- exiting ' . __function__ . \PHP_EOL ;
    }
    
}

?>
