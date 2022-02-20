<?php namespace Stader\Control\Objects\AreaPlace ;

use \Stader\Control\Abstract\DataObjectsDao ;
use \Stader\Model\Tables\User\{UserLogin,UsersLogin} ;

class Users extends DataObjectsDao
{
    public static $allowedKeys = 
        [ 'areaplace' => 'varchar' 
        ] ;
    protected   $class  = '\\Stader\\Control\\AreaPlace\\AreaPlace' ;
    private UserLogin  $userLogin  ;
    private UsersLogin $usersLogin ;

    function __construct ( ...$args )
    {   // echo 'class Users extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;
        
        parent::__construct ( self::$allowedKeys  ) ;

        $this->setupData( $args ) ;
        $this->usersLogin = new UsersLogin() ;

    }

    public function rewind() : void 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        $this->usersLogin->rewind() ;
        $this->position = 0 ;
    }

    public function count() : int 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        return $this->usersLogin->count() ;
    }

    public function next() : void 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        $this->usersLogin->next() ;
        ++$this->position ;
    }

    public function valid() : bool 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        return $this->usersLogin->valid() ;
    }

    public function current() : Object 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        $this->userLogin = $this->usersLogin->current() ;
        return $this->getOne( $this->userLogin->getData()['id'] ) ;
    }

    public function key() : int | false 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        return $this->usersLogin->key() ;
    }

    public function deleteAll() : void 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        $this->usersLogin->deleteAll() ;
    }

    public function getIDs() : array 
    {
        return $this->usersLogin->getIDs() ;
    }
}

?>
