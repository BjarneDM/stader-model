<?php namespace Stader\Model\Tables\TicketGroup ;

use \Stader\Model\Abstract\DataObjectDao ;
use \Stader\Model\Traits\DataObjectConstruct ;

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

class TicketGroup extends DataObjectDao
{
    public static $allowedKeys = 
        [ 'ticket_id' => 'int' , 
          'group_id'  => 'int' 
        ] ;
    public static $thisClass   = '\\Stader\\Model\\Tables\\TicketGroup\\TicketGroup' ;

    use DataObjectConstruct ;

    function fixValuesType () : void
    {
        $this->values['ticket_id'] = (int) $this->values['ticket_id'] ;
        $this->values['group_id']  = (int) $this->values['group_id']  ;

    }

}

?>
