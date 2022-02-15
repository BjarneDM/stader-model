<?php namespace Stader\Model\Tables\User ;

use \Stader\Model\Abstract\DataObjectsDao ;

class UsersInfo extends DataObjectsDao
{

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        $this->keysAllowed = ( new \ArrayObject( UserInfo::$allowedKeys ) )->getArrayCopy() ;
        $this->class = UserInfo::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
