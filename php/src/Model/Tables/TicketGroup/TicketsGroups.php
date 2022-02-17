<?php namespace Stader\Model\Tables\TicketGroup ;

use \Stader\Model\Abstract\DataObjectsDao ;

class TicketsGroups extends DataObjectsDao
{
    public static $allowedKeys = [] ;
    public static $thisClass   = '' ;

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        self::$allowedKeys = ( new \ArrayObject( TicketGroup::$allowedKeys ) )->getArrayCopy() ;
        $this->keysAllowed = ( new \ArrayObject( TicketGroup::$allowedKeys ) )->getArrayCopy() ;
        self::$thisClass = TicketGroup::$thisClass ;
        $this->class     = TicketGroup::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
