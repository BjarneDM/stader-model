<?php namespace Stader\Model\Traits ;

trait DataObjectConstruct
{
    function __construct ( ...$args )
    {   // echo 'class UGroup extends DataObjectDao __construct' . \PHP_EOL ;
        // print_r( $this ) ;

        $this->keysAllowed = ( new \ArrayObject( self::$allowedKeys ) )->getArrayCopy() ;
        $this->class       = self::$thisClass ;
        $this->database    = self::$dbType ;
        $this->setValuesDefault ( $args ) ;
        parent::__construct() ;
        $this->setupObject( $args ) ;
        $this->fixValuesType () ;

    }
}

?>
