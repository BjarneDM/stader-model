<?php namespace Stader\Model\Tables\Ticket ;

use \Stader\Model\Abstract\ObjectDao ;
use \Stader\Model\OurDateTime ;

/*

create table if not exists ticket
(
    id                  int auto_increment primary key ,
    header              varchar(255) not null ,
    description         text not null ,
    assigned_place_id   int ,
        foreign key (assigned_place_id) references place(id)
        on update cascade 
        on delete restrict ,
    ticket_status_id    int not null ,
        foreign key (ticket_status_id) references ticketstatus(id)
        on update cascade 
        on delete restrict ,
    assigned_user_id    int default null ,
        foreign key (assigned_user_id) references usercrypt(id)
        on update cascade 
        on delete restrict ,
    creationtime        datetime
        default   current_timestamp ,
    lastupdatetime       datetime
        default   current_timestamp
        on update current_timestamp ,
    active              boolean default true
) ;

 */

class Ticket extends ObjectDao
{
    public static $allowedKeys = 
        [ 'header'            => 'varchar' , 
          'description'       => 'text'    , 
          'assigned_place_id' => 'int'     , 
          'ticket_status_id'  => 'int'     , 
          'assigned_user_id'  => 'int'     , 
          'active'            => 'bool'
        ] ;
    protected   $class  = '\\Stader\\Model\\Tables\\Ticket\\Ticket' ;

    function __construct ( ...$args )
    {   // echo 'class Ticket extends ObjectDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;
        $this->values['assigned_place_id'] = (int) $this->values['assigned_place_id'] ;
        $this->values['ticket_status_id']  = (int) $this->values['ticket_status_id']  ;
        $this->values['assigned_user_id']  = (int) $this->values['assigned_user_id']  ;
        $this->values['creationtime']      = @is_null( $this->values['creationtime']   ) 
                                             ? new OurDateTime()
                                             : OurDateTime::createFromFormat( 'Y-m-d H:i:s' , $this->values['creationtime']   ) ;
        $this->values['lastupdatetime']    = @is_null( $this->values['lastupdatetime'] ) 
                                             ? null 
                                             : OurDateTime::createFromFormat( 'Y-m-d H:i:s' , $this->values['lastupdatetime'] ) ;

    }

}

?>
