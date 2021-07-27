<?php namespace stader\model ;

/*

create table if not exists ticketlog
(
    id              int auto_increment primary key ,
    ticket_id       int not null ,
        index (ticket_id) ,
    header          varchar(255) ,
        index (header) ,
    log_timestamp   datetime
        default current_timestamp ,
        index (log_timestamp) ,
    old_value       text default null ,
    new_value       text default null
) ;

 */

class TicketLog extends ObjectDao
{
    private $allowedKeys = 
        [ 'ticket_id' => 'int'     , 
          'header'    => 'varchar' , 
          'old_value' => 'text'    , 
          'new_value' => 'text' 
        ] ;

    function __construct ( ...$args )
    {   // echo 'class TicketLog extends ObjectDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'logs' , self::$allowedKeys , $args ) ;

        $this->setupLogs( $args ) ;

    }

}

?>
