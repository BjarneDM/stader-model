<?php namespace Stader\Model\Tables\Ticket ;

use \Stader\Model\Abstract\DataObjectsDao ;

class Tickets extends DataObjectsDao
{
    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        $this->keysAllowed = ( new \ArrayObject( Ticket::$allowedKeys ) )->getArrayCopy() ;
        $this->class = Ticket::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
