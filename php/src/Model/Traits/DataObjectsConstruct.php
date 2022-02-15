<?php namespace Stader\Model\Traits ;

/*
    Dette her fungerer ikke endnu, 
    idet der er et eksplicit kald t/ den grundlæggede Class
    & dette kald skal så på en-eller-anden måde abstraheres
 */

trait DataObjectsConstruct
{
    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        $this->keysAllowed = ( new \ArrayObject( UGroup::$allowedKeys ) )->getArrayCopy() ;
        $this->class = UGroup::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }
}

?>
