<?php namespace Stader\Model\Traits ;

trait DataObjectConstruct
{
    function __construct ( ...$args )
    {   // echo "class ". self::$thisClass ." extends DataObjectDao __construct" . \PHP_EOL ;
        // echo self::$thisClass . \PHP_EOL ;
        // print_r( $args ) ;

        $this->setValuesDefault ( $args ) ;
        parent::__construct( dbType: self::$dbType , thisClass: self::$thisClass , allowedKeys: self::$allowedKeys  ) ;
        $this->setupObject( self::$thisClass , $args ) ;
        $this->fixValuesType () ;

    }
}

?>
