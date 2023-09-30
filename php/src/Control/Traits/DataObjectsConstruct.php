<?php namespace Stader\Control\Traits ;

trait DataObjectsConstruct
{

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        parent::__construct() ;
        $this->setupData( self::$thisClass , $args ) ;

    }
}

?>
