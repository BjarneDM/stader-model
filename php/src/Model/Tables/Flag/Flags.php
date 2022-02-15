<?php namespace Stader\Model\Tables\Flag ;

use \Stader\Model\Abstract\DataObjectsDao ;

class Flags extends DataObjectsDao
{
    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        $this->keysAllowed = ( new \ArrayObject( Flag::$allowedKeys ) )->getArrayCopy() ;
        $this->class = Flag::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
