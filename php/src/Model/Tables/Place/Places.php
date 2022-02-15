<?php namespace Stader\Model\Tables\Place ;

use \Stader\Model\Abstract\DataObjectsDao ;

class Places extends DataObjectsDao
{
    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        $this->keysAllowed = ( new \ArrayObject( Place::$allowedKeys ) )->getArrayCopy() ;
        $this->class = Place::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
