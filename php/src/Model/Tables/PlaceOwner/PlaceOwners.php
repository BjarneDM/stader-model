<?php namespace Stader\Model\Tables\PlaceOwner ;

use \Stader\Model\Abstract\DataObjectsDao ;

class PlaceOwners extends DataObjectsDao
{
    public static $allowedKeys = [] ;
    public static $thisClass   = '' ;

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        self::$allowedKeys = ( new \ArrayObject( PlaceOwner::$allowedKeys ) )->getArrayCopy() ;
        $this->keysAllowed = ( new \ArrayObject( PlaceOwner::$allowedKeys ) )->getArrayCopy() ;
        self::$thisClass = PlaceOwner::$thisClass ;
        $this->class     = PlaceOwner::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
