<?php namespace Stader\Model\Tables\UserRole ;

use \Stader\Model\Abstract\DataObjectsDao ;

class UsersRoles extends DataObjectsDao
{
    public static $allowedKeys = [] ;
    public static $thisClass   = '' ;

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        self::$allowedKeys = ( new \ArrayObject( UserRole::$allowedKeys ) )->getArrayCopy() ;
        $this->keysAllowed = ( new \ArrayObject( UserRole::$allowedKeys ) )->getArrayCopy() ;
        self::$thisClass = UserRole::$thisClass ;
        $this->class     = UserRole::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
