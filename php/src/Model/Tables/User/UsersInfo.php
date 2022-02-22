<?php namespace Stader\Model\Tables\User ;

use \Stader\Model\Abstract\DataObjectsDao ;
use \Stader\Model\Traits\DataObjectsConstruct ;

class UsersInfo extends DataObjectsDao
{
    private static $class = '\\Stader\\Model\\Tables\\User\\UserInfo' ;

    use DataObjectsConstruct ;

}

?>
