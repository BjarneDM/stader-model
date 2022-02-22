<?php namespace Stader\Control\Traits ;

trait DataObjectsConstruct
{
    public static $allowedKeys ;
    public static $thisClass   ;

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        if ( ! self::$allowedKeys ) self::$allowedKeys = ( new \ArrayObject( self::$baseClass::$allowedKeys ) )->getArrayCopy() ;
        if ( ! self::$thisClass   ) self::$thisClass   = self::$baseClass::$thisClass ;

        parent::__construct() ;
        $this->setupData( self::$thisClass , $args ) ;

    }
}

?>
