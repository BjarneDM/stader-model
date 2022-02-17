<?php namespace Stader\Model\Tables\Group ;

use \Stader\Model\Abstract\DataObjectsDao ;

class UGroups extends DataObjectsDao
{
    public static $allowedKeys = [] ;
    public static $thisClass   = '' ;

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        self::$allowedKeys = ( new \ArrayObject( UGroup::$allowedKeys ) )->getArrayCopy() ;
        $this->keysAllowed = ( new \ArrayObject( UGroup::$allowedKeys ) )->getArrayCopy() ;
        self::$thisClass = UGroup::$thisClass ;
        $this->class     = UGroup::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
