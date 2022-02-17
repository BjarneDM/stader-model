<?php namespace Stader\Model\Tables\User ;

use \Stader\Model\Abstract\DataObjectsDao ;

class UsersInfo extends DataObjectsDao
{
    public static $allowedKeys = [] ;
    public static $thisClass   = '' ;

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        self::$allowedKeys = ( new \ArrayObject( UserInfo::$allowedKeys ) )->getArrayCopy() ;
        $this->keysAllowed = ( new \ArrayObject( UserInfo::$allowedKeys ) )->getArrayCopy() ;
        self::$thisClass = UserInfo::$thisClass ;
        $this->class     = UserInfo::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
