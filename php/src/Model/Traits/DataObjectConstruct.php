<?php namespace Stader\Model\Traits ;

trait DataObjectConstruct
{
    function __construct ( ...$args )
    {   // echo 'trait DataObjectConstruct __construct' . \PHP_EOL ;
        // echo self::$thisClass . \PHP_EOL ;
        // print_r( $args ) ;

        $this->setValuesDefault ( $args ) ;
        parent::__construct( self::$dbType , self::$class ,self::$allowedKeys  ) ;
        $this->setupObject( self::$class , $args ) ;
        $this->fixValuesType () ;

    }
}

?>
