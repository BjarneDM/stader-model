<?php namespace Stader\Model\Tables\User ;

use \Stader\Model\Abstract\DataObjectsDao ;
use \Stader\Model\Traits\DataObjectsConstruct ;

class UsersInfo extends DataObjectsDao
{
    private static $baseClass = '\\Stader\\Model\\Tables\\User\\UserInfo' ;

    use DataObjectsConstruct ;

}

?>
