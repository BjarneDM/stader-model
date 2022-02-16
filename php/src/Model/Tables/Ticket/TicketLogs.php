<?php namespace Stader\Model\Tables\Ticket ;

use \Stader\Model\Abstract\LogObjectsDao ;


class TicketLogs extends LogObjectsDao
{

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        $this->keysAllowed = ( new \ArrayObject( TicketLog::$allowedKeys ) )->getArrayCopy() ;
        $this->class = TicketLog::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
