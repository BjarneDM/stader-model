<?php namespace Stader\Model\Tables\User ;

use \Stader\Model\Abstract\DataObjectsDao ;

class UsersLogin extends DataObjectsDao
{

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        $this->keysAllowed = ( new \ArrayObject( UserLogin::$allowedKeys ) )->getArrayCopy() ;
        $this->class = UserLogin::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
