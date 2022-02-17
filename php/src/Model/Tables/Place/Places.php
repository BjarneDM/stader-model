<?php namespace Stader\Model\Tables\Place ;

use \Stader\Model\Abstract\DataObjectsDao ;

class Places extends DataObjectsDao
{
    public static $allowedKeys = [] ;
    public static $thisClass   = '' ;

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        self::$allowedKeys = ( new \ArrayObject( Place::$allowedKeys ) )->getArrayCopy() ;
        $this->keysAllowed = ( new \ArrayObject( Place::$allowedKeys ) )->getArrayCopy() ;
        self::$thisClass = Place::$thisClass ;
        $this->class     = Place::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
