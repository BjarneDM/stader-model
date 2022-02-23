<?php namespace Stader\Model\Traits ;

trait DataObjectsConstruct
{
    public static $dbType      ;
    public static $thisClass   ;
    public static $allowedKeys ;

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        if ( ! self::$dbType      ) self::$dbType      = self::$baseClass::$dbType ;
        if ( ! self::$thisClass   ) self::$thisClass   = self::$baseClass::$thisClass ;
        if ( ! self::$allowedKeys ) self::$allowedKeys = 
            ( new \ArrayObject( self::$baseClass::$allowedKeys ) )->getArrayCopy() ;

        parent::__construct( 
            dbType      : self::$dbType , 
            thisClass   : self::$thisClass , 
            allowedKeys : self::$allowedKeys  ) ;
        $this->setupData( $args ) ;

    }
}

?>
