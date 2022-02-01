<?php namespace Stader\Model\Tables\TicketGroup ;

use \Stader\Model\Abstract\ObjectDao ;

/*

create table if not exists ticketgroup
(
    id                  int auto_increment primary key ,
    ticket_id           int ,
        foreign key (ticket_id) references ticket(id)
        on update cascade 
        on delete cascade ,
    group_id            int ,
        foreign key (group_id) references usergroup(id)
        on update cascade 
        on delete restrict
) ;

 */

class TicketGroup extends ObjectDao
{
    public static $allowedKeys = 
        [ 'ticket_id' => 'int' , 
          'group_id'  => 'int' 
        ] ;
    protected   $class  = '\\Stader\\Model\\Tables\\TicketGroup\\TicketGroup' ;

    function __construct ( ...$args )
    {   // echo 'class TicketGroup extends ObjectDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;
        $this->values['ticket_id'] = (int) $this->values['ticket_id'] ;
        $this->values['group_id']  = (int) $this->values['group_id']  ;

    }

}

?>
