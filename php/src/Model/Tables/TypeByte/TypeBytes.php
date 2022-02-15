<?php namespace Stader\Model\Tables\TypeByte ;

use \Stader\Model\Abstract\DataObjectsDao ;

class TypeBytes extends DataObjectsDao
{
    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        $this->keysAllowed = ( new \ArrayObject( TypeByte::$allowedKeys ) )->getArrayCopy() ;
        $this->class = TypeByte::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
