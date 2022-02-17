<?php namespace Stader\Model\Tables\User ;

use \Stader\Model\Abstract\DataObjectsDao ;

class UsersLogin extends DataObjectsDao
{
    public static $allowedKeys = [] ;
    public static $thisClass   = '' ;

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        self::$allowedKeys = ( new \ArrayObject( UserLogin::$allowedKeys ) )->getArrayCopy() ;
        $this->keysAllowed = ( new \ArrayObject( UserLogin::$allowedKeys ) )->getArrayCopy() ;
        self::$thisClass = UserLogin::$thisClass ;
        $this->class     = UserLogin::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
