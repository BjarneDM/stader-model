<?php namespace Stader\Model\Tables\Beredskab ;

use \Stader\Model\Abstract\DataObjectsDao ;

class Beredskabs extends DataObjectsDao
{
    public static $allowedKeys = [] ;
    public static $thisClass   = '' ;

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        self::$allowedKeys = ( new \ArrayObject( Beredskab::$allowedKeys ) )->getArrayCopy() ;
        $this->keysAllowed = ( new \ArrayObject( Beredskab::$allowedKeys ) )->getArrayCopy() ;
        self::$thisClass = Beredskab::$thisClass ;
        $this->class     = Beredskab::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
