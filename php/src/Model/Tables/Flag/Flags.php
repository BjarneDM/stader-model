<?php namespace Stader\Model\Tables\Flag ;

use \Stader\Model\Abstract\DataObjectsDao ;

class Flags extends DataObjectsDao
{
    public static $allowedKeys = [] ;
    public static $thisClass   = '' ;

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        self::$allowedKeys = ( new \ArrayObject( Flag::$allowedKeys ) )->getArrayCopy() ;
        $this->keysAllowed = ( new \ArrayObject( Flag::$allowedKeys ) )->getArrayCopy() ;
        self::$thisClass = Flag::$thisClass ;
        $this->class     = Flag::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
