<?php namespace Stader\Model\Tables\Ticket ;

use \Stader\Model\Abstract\DataObjectDao ;
use \Stader\Model\DateTimeString ;
use \Stader\Model\Traits\{DataObjectConstruct,LogNotify} ;

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
        foreign key (assigned_user_id) references userlogin(id)
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

class Ticket extends DataObjectDao
{
    public static $dbType      = 'data' ;
    public static $thisClass   = '\\Stader\\Model\\Tables\\Ticket\\Ticket' ;
    public static $allowedKeys = 
        [ 'header'            => 'varchar' , 
          'description'       => 'text'    , 
          'assigned_place_id' => 'int'     , 
          'ticket_status_id'  => 'int'     , 
          'assigned_user_id'  => 'int'     , 
          'active'            => 'bool'
        ] ;
    public static $privateKeys = [] ;
    private $thisLog ;
    private $referenceID ;
    private $descriptID  ;

    use DataObjectConstruct ;

    protected function setValuesDefault ( &$args ) : void
    {
        $this->thisLog = self::$thisClass . 'Log' ;
        $this->referenceID = array_keys( TicketLog::$allowedKeys )[0] ;
        $this->descriptID  = array_keys( TicketLog::$allowedKeys )[1] ;
    }

    protected function fixValuesType () : void
    {
        $this->values['assigned_place_id'] = (int) $this->values['assigned_place_id'] ;
        $this->values['ticket_status_id']  = (int) $this->values['ticket_status_id']  ;
        $this->values['assigned_user_id']  = (int) $this->values['assigned_user_id']  ;
        $this->values['creationtime']
        =   @is_null( $this->values['creationtime']   ) 
            ? new DateTimeString()
            : DateTimeString::createFromFormat( 'Y-m-d H:i:s' , $this->values['creationtime']   ) ;
        $this->values['lastupdatetime']
        =   @is_null( $this->values['lastupdatetime'] ) 
            ? null 
            : DateTimeString::createFromFormat( 'Y-m-d H:i:s' , $this->values['lastupdatetime'] ) ;
    }

    use LogNotify ;

}

?>
