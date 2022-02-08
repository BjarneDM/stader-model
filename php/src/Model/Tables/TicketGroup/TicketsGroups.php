<?php namespace Stader\Model\Tables\TicketGroup ;

use \Stader\Model\Abstract\DataObjectsDao ;

class TicketsGroups extends DataObjectsDao
{
    public static $allowedKeys = 
        [ 'ticket_id' => 'int' , 
          'group_id'  => 'int' 
        ] ;
    protected   $class  = '\\Stader\\Model\\Tables\\TicketGroup\\TicketGroup' ;

    function __construct ( ...$args )
    {   // echo 'class TicketGroups extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
