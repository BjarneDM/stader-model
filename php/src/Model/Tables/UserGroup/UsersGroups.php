<?php namespace Stader\Model\Tables\UserGroup ;

use \Stader\Model\Abstract\DataObjectsDao ;

class UsersGroups extends DataObjectsDao
{
    public static $allowedKeys = [] ;
    public static $thisClass   = '' ;

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        self::$allowedKeys = ( new \ArrayObject( UserGroup::$allowedKeys ) )->getArrayCopy() ;
        $this->keysAllowed = ( new \ArrayObject( UserGroup::$allowedKeys ) )->getArrayCopy() ;
        self::$thisClass = UserGroup::$thisClass ;
        $this->class     = UserGroup::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
