<?php namespace Stader\Model\Tables\Ticket ;

use \Stader\Model\Abstract\LogObjectsDao ;


class TicketLogs extends LogObjectsDao
{
    private $allowedKeys = 
        [ 'ticket_id' => 'int'     , 
          'header'    => 'varchar' , 
          'old_value' => 'text'    , 
          'new_value' => 'text' 
        ] ;

    function __construct ( ...$args )
    {   // echo 'class TicketLogs extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'logs' , self::$allowedKeys , $args ) ;

        $this->setupData( $args ) ;

    }

}

?>
