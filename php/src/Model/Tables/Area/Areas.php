<?php namespace Stader\Model\Tables\Area ;

use \Stader\Model\Abstract\DataObjectsDao ;

class Areas extends DataObjectsDao
{
    public static $allowedKeys = [] ;
    public static $thisClass   = '' ;

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        self::$allowedKeys = ( new \ArrayObject( Area::$allowedKeys ) )->getArrayCopy() ;
        $this->keysAllowed = ( new \ArrayObject( Area::$allowedKeys ) )->getArrayCopy() ;
        self::$thisClass = Area::$thisClass ;
        $this->class     = Area::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
