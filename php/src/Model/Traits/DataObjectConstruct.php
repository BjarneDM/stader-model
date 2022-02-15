<?php namespace Stader\Model\Traits ;

trait DataObjectConstruct
{
    function __construct ( ...$args )
    {   // echo 'class UGroup extends ObjectDao __construct' . \PHP_EOL ;
        // var_dump( $args ) ;

        $this->keysAllowed = ( new \ArrayObject( self::$allowedKeys ) )->getArrayCopy() ;
        $this->class = self::$thisClass ;
        $this->setValuesDefault ( $args ) ;
        parent::__construct() ;
        $this->setupData( $args ) ;
        $this->fixValuesType () ;

    }
}

?>
