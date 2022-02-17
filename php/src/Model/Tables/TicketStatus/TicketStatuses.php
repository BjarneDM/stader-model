<?php namespace Stader\Model\Tables\TicketStatus ;

use \Stader\Model\Abstract\DataObjectsDao ;

class TicketStatuses extends DataObjectsDao
{
    public static $allowedKeys = [] ;
    public static $thisClass   = '' ;

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        self::$allowedKeys = ( new \ArrayObject( TicketStatus::$allowedKeys ) )->getArrayCopy() ;
        $this->keysAllowed = ( new \ArrayObject( TicketStatus::$allowedKeys ) )->getArrayCopy() ;
        self::$thisClass = TicketStatus::$thisClass ;
        $this->class     = TicketStatus::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
