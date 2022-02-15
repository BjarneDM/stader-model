<?php namespace Stader\Model\Tables\Group ;

use \Stader\Model\Abstract\DataObjectsDao ;

class UGroups extends DataObjectsDao
{

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        $this->keysAllowed = ( new \ArrayObject( UGroup::$allowedKeys ) )->getArrayCopy() ;
        $this->class = UGroup::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
