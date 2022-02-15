<?php namespace Stader\Model\Tables\UserRole ;

use \Stader\Model\Abstract\DataObjectsDao ;

class UsersRoles extends DataObjectsDao
{

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        $this->keysAllowed = ( new \ArrayObject( UserRole::$allowedKeys ) )->getArrayCopy() ;
        $this->class = UserRole::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
