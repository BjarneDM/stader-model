<?php namespace Stader\Model\Traits ;

/*
    Dette her fungerer ikke endnu, 
    idet der er et eksplicit kald t/ den grundlæggede Class
    & dette kald skal så på en-eller-anden måde abstraheres
 */

trait DataObjectsConstruct
{
    public static $dbType ;
    public static $allowedKeys ;
    public static $thisClass   ;

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        if ( ! self::$allowedKeys ) self::$allowedKeys = ( new \ArrayObject( self::$baseClass::$allowedKeys ) )->getArrayCopy() ;
        $this->keysAllowed = ( new \ArrayObject( self::$baseClass::$allowedKeys ) )->getArrayCopy() ;

        if ( ! self::$thisClass ) self::$thisClass   = self::$baseClass::$thisClass ;
        $this->class = self::$baseClass::$thisClass ;

        if ( ! self::$dbType ) self::$dbType   = self::$baseClass::$dbType ;
        $this->database = self::$baseClass::$dbType ;

        parent::__construct() ;
        $this->setupData( $args ) ;

    }
}

?>
