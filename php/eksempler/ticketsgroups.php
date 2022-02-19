<?php namespace Stader\Eksempler ;

require_once( 'classloader.php' ) ;

use \Stader\Model\Tables\TicketGroup\{TicketGroup,TicketsGroups} ;


$tickets_groups = new TicketsGroups( 'ticket_id' , 1 ) ;
print_r( $tickets_groups ) ;
foreach ( $tickets_groups as $ticket_group ) 
{
    print_r( $ticket_group->getData() ) ;
    $ticket_group->delete() ;
}

?>
