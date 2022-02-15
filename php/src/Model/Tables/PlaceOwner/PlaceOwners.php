<?php namespace Stader\Model\Tables\PlaceOwner ;

use \Stader\Model\Abstract\DataObjectsDao ;

class PlaceOwners extends DataObjectsDao
{
    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        $this->keysAllowed = ( new \ArrayObject( PlaceOwner::$allowedKeys ) )->getArrayCopy() ;
        $this->class = PlaceOwner::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
