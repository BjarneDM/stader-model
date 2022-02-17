<?php namespace Stader\Model\Tables\Role ;

use \Stader\Model\Abstract\DataObjectsDao ;

class URoles extends DataObjectsDao
{
    public static $allowedKeys = [] ;
    public static $thisClass   = '' ;

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        self::$allowedKeys = ( new \ArrayObject( URole::$allowedKeys ) )->getArrayCopy() ;
        $this->keysAllowed = ( new \ArrayObject( URole::$allowedKeys ) )->getArrayCopy() ;
        self::$thisClass = URole::$thisClass ;
        $this->class     = URole::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
