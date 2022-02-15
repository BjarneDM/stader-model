<?php namespace Stader\Model\Tables\TicketStatus ;

use \Stader\Model\Abstract\DataObjectsDao ;

class TicketStatuses extends DataObjectsDao
{
    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        $this->keysAllowed = ( new \ArrayObject( TicketStatus::$allowedKeys ) )->getArrayCopy() ;
        $this->class = TicketStatus::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
