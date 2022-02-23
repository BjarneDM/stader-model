<?php namespace Stader\Model\Tables\Ticket ;

use \Stader\Model\Abstract\DataObjectDao ;
use \Stader\Model\OurDateTime ;
use \Stader\Model\Traits\{DataObjectConstruct,LogFunctions} ;

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

class TicketLog extends DataObjectDao
{
    public static $dbType      = 'logs' ;
    public static $thisClass   = '\\Stader\\Model\\Tables\\Ticket\\TicketLog' ;
    public static $allowedKeys = 
        [ 'ticket_id' => 'int'     , 
          'header'    => 'varchar' , 
          'old_value' => 'text'    , 
          'new_value' => 'text' 
        ] ;
    private $referenceID ;
    private $descriptID  ;

    use DataObjectConstruct ;
    use LogFunctions ;

}

?>
