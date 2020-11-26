<?php   namespace stader\bootstrap ;

echo '<pre>' ;
echo \PHP_EOL . str_repeat( '-' , 50 ) . \PHP_EOL . '-> entering : ' . basename( __file__ ) . \PHP_EOL ;



/*

create table tickets_groups
(
    tickets_groups_id     int auto_increment primary key ,
    ticket_id           int ,
        foreign key (ticket_id) references tickets(ticket_id)
        on update cascade 
        on delete cascade ,
    group_id            int ,
        foreign key (group_id) references ticket_groups(group_id)
        on update cascade 
        on delete cascade
) ;

 */


/*
 *  setup
 */

require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;
use \stader\model\{Group,Groups,Ticket,Tickets,TicketGroup,TicketsGroups} ;

/*
 *  data
 */

$ticketNames = [] ;
// $ticketNames['Frontend']            = [ 0 ] ;
// $ticketNames['Logic']               = [ 0 , 1 ] ;
// $ticketNames['Klargøring']          = [ 0 , 1 , 2 ] ;
// $ticketNames['Transport']           = [ 0 , 1 , 2 , 3 , 7 ] ;
// $ticketNames['Runderings Tekniker'] = [ 0 , 1 , 2 , 3 , 4 , 6 ] ;
// $ticketNames['Data']                   = [ 0 , 1 , 2 , 3 , 4 , 5 ] ;
// $ticketNames['Backend']                = [ 0 , 1 , 2 , 3 ] ;
// $ticketNames['Fejl 40']                = [ 0 , 1 , 2 , 3 , 4 ] ;

/*
 *  main
 */

$allGroups = new Groups() ;
// print_r( $allGroups ) ;
foreach ( $allGroups->getGroups() as $group )
{
//     print_r( $group ) ; // exit() ;
//     var_dump( $group->getData()['name'] ) ;
//     var_dump( $ticketNames[ $group->getData()['name'] ] ) ;
    foreach ( $ticketNames[ $group->getData()['name'] ] as $ticketNbr )
    {
        $thisTicket = new Ticket( 'header' , $tickets[$ticketNbr]['header'] ) ;
//         print_r( [ 'ticket_id' => $thisTicket->getData()['ticket_id'] , 'group_id' => $group->getData()['group_id'] ] ) ;
        $newTicketGroup = new TicketGroup( [ 'ticket_id' => $thisTicket->getData()['ticket_id'] , 'group_id' => $group->getData()['group_id'] ] ) ;
//         print_r( $newTicketGroup->getData() ) ;
    }   unset( $ticketNbr ) ;
}

$allTicketsGroups = new TicketsGroups() ;
foreach ( $allTicketsGroups->getTicketsGroups() as $ticketgroup )
{
    $groupticket = $ticketgroup->getData() ;
    $groupticket['group_id'] = ( new Group( $ticketgroup->getData()['group_id'] ) )->getData()['name'] ;
    echo json_encode( $groupticket , JSON_UNESCAPED_UNICODE ) . \PHP_EOL ;
}
    unset( $ticketgroup , $allTicketsGroups ) ;

echo '</pre>' ;
?>
