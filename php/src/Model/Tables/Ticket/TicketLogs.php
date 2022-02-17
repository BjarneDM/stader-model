<?php namespace Stader\Model\Tables\Ticket ;

use \Stader\Model\Abstract\LogObjectsDao ;


class TicketLogs extends LogObjectsDao
{
    public static $allowedKeys = [] ;
    public static $thisClass   = '' ;

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        self::$allowedKeys = ( new \ArrayObject( TicketLog::$allowedKeys ) )->getArrayCopy() ;
        $this->keysAllowed = ( new \ArrayObject( TicketLog::$allowedKeys ) )->getArrayCopy() ;
        self::$thisClass = TicketLog::$thisClass ;
        $this->class     = TicketLog::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
