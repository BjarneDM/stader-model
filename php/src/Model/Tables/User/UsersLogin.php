<?php namespace Stader\Model\Tables\User ;

use \Stader\Model\Abstract\DataObjectsDao ;
use \Stader\Model\Traits\{DataObjectsConstruct,ObjectsDaoIterator} ;

class UsersLogin extends DataObjectsDao
{
    private static $baseClass = '\\Stader\\Model\\Tables\\User\\UserLogin' ;

    use DataObjectsConstruct ;

}

?>
