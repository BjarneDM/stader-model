<?php namespace Stader\Model\Tables\TicketGroup ;

use \Stader\Model\Abstract\DataObjectsDao ;

class TicketsGroups extends DataObjectsDao
{
    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        $this->keysAllowed = ( new \ArrayObject( TicketGroup::$allowedKeys ) )->getArrayCopy() ;
        $this->class = TicketGroup::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
