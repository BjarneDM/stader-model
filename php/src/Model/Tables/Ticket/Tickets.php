<?php namespace Stader\Model\Tables\Ticket ;

use \Stader\Model\Abstract\DataObjectsDao ;

class Tickets extends DataObjectsDao
{
    public static $allowedKeys = [] ;
    public static $thisClass   = '' ;

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        self::$allowedKeys = ( new \ArrayObject( Ticket::$allowedKeys ) )->getArrayCopy() ;
        $this->keysAllowed = ( new \ArrayObject( Ticket::$allowedKeys ) )->getArrayCopy() ;
        self::$thisClass = Ticket::$thisClass ;
        $this->class     = Ticket::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
