<?php namespace Stader\Model\Tables\UserBeredskab ;

use \Stader\Model\Abstract\DataObjectsDao ;

class UsersBeredskabs extends DataObjectsDao
{
    public static $allowedKeys = [] ;
    public static $thisClass   = '' ;

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        self::$allowedKeys = ( new \ArrayObject( UserBeredskab::$allowedKeys ) )->getArrayCopy() ;
        $this->keysAllowed = ( new \ArrayObject( UserBeredskab::$allowedKeys ) )->getArrayCopy() ;
        self::$thisClass = UserBeredskab::$thisClass ;
        $this->class     = UserBeredskab::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
