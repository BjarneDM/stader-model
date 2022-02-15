<?php namespace Stader\Model\Tables\UserGroup ;

use \Stader\Model\Abstract\DataObjectsDao ;

class UsersGroups extends DataObjectsDao
{

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        $this->keysAllowed = ( new \ArrayObject( UserGroup::$allowedKeys ) )->getArrayCopy() ;
        $this->class = UserGroup::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
