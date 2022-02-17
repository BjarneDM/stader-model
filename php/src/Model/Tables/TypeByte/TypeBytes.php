<?php namespace Stader\Model\Tables\TypeByte ;

use \Stader\Model\Abstract\DataObjectsDao ;

class TypeBytes extends DataObjectsDao
{
    public static $allowedKeys = [] ;
    public static $thisClass   = '' ;

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        self::$allowedKeys = ( new \ArrayObject( TypeByte::$allowedKeys ) )->getArrayCopy() ;
        $this->keysAllowed = ( new \ArrayObject( TypeByte::$allowedKeys ) )->getArrayCopy() ;
        self::$thisClass = TypeByte::$thisClass ;
        $this->class     = TypeByte::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
