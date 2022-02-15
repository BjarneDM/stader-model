<?php namespace Stader\Model\Tables\Role ;

use \Stader\Model\Abstract\DataObjectsDao ;

class URoles extends DataObjectsDao
{
    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        $this->keysAllowed = ( new \ArrayObject( URole::$allowedKeys ) )->getArrayCopy() ;
        $this->class = URole::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
