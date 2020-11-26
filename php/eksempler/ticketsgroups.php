<?php namespace stader\eksempler ;

require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;

use \stader\model\{TicketGroup,TicketsGroups} ;


$tickets_groups = new TicketsGroups( 'ticket_id' , 1 ) ;
print_r( $tickets_groups ) ;
foreach ( $tickets_groups->getTicketsGroups() as $ticket_group ) 
{
    print_r( $ticket_group->getData() ) ;
    $ticket_group->delete() ;
}

?>
