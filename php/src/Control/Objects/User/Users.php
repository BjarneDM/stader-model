<?php namespace Stader\Control\Objects\User ;

use \Stader\Control\Abstract\DataObjectsDao ;
use \Stader\Model\Tables\User\{UserLogin,UsersLogin} ;
use \Stader\Control\Traits\DataObjectsConstruct ;

class Users extends DataObjectsDao
{
    public static $baseClass  = '\\Stader\\Control\\Objects\\User\\User' ;
    public static $thisClass  = '\\Stader\\Control\\Objects\\User\\User' ;

    private UserLogin  $userLogin  ;
    private UsersLogin $usersLogin ;

    use DataObjectsConstruct ;

    protected function setupData ( $thisClass , $args )
    {
        parent::setupData( $thisClass , $args ) ;
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
        return $this->getOne( $this->userLogin , $this->userLogin->getData()['id'] ) ;
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
