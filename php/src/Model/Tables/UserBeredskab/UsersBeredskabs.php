<?php namespace Stader\Model\Tables\UserBeredskab ;

use \Stader\Model\Abstract\DataObjectsDao ;

class UsersBeredskabs extends DataObjectsDao
{

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        $this->keysAllowed = ( new \ArrayObject( UserBeredskab::$allowedKeys ) )->getArrayCopy() ;
        $this->class = UserBeredskab::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
