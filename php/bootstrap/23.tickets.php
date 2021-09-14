<?php   namespace stader\bootstrap ;

echo '<pre>' ;
echo \PHP_EOL . str_repeat( '-' , 50 ) . \PHP_EOL . '-> entering : ' . basename( __file__ ) . \PHP_EOL ;



/*

create table tickets
(
    id                  int auto_increment primary key ,
    header              varchar(255) not null ,
    description         text not null ,
    ticket_status_id    int not null ,
    assigned_user_id    int ,
        foreign key (assigned_user_id) references usersCrypt(id)
        on update cascade 
        on delete restrict ,
    active              boolean
) ;

 */


/*
 *  setup
 */

require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;
use \stader\model\{User,Users,Ticket,Tickets,Area,Place,TicketStatus} ;

/*
 *  data
 */

$tickets =
[
    [ 'assigned_place_id' => 'A1' , 'header' => 'mangler batteri.' , 'description' => '' , 'ticket_status_id' => 'Pakning'  , 'assigned_user_id' => null , 'active' => true ] ,
    [ 'assigned_place_id' => 'S1' , 'header' => 'Hund skidt på teltet ved lars løkke.' , 'description' => '' , 'ticket_status_id' => 'Opstart Morgen'  , 'assigned_user_id' => null , 'active' => true ] ,
    [ 'assigned_place_id' => 'C3' , 'header' => 'Barn hoppet i vandet, og svanerne har spist ungen.' , 'description' => '' , 'ticket_status_id' => 'AKUT fejlmelding'  , 'assigned_user_id' => null , 'active' => true ] ,
    [ 'assigned_place_id' => 'D5' , 'header' => 'Er der nogen der har set min sko?.' , 'description' => '' , 'ticket_status_id' => 'OK'  , 'assigned_user_id' => null , 'active' => true ] ,
    [ 'assigned_place_id' => 'B3' , 'header' => 'Brand i transformer' , 'description' => '' , 'ticket_status_id' => 'AKUT fejlmelding'  , 'assigned_user_id' => null , 'active' => true ] ,
    [ 'assigned_place_id' => 'A1' , 'header' => 'Telt mangler pløgger' , 'description' => '' , 'ticket_status_id' => 'Opsætning'  , 'assigned_user_id' => null , 'active' => true ] ,
    [ 'assigned_place_id' => 'S1' , 'header' => 'Køkken hos spejderne mangler olie' , 'description' => '' , 'ticket_status_id' => 'Mangler Udstyr' , 'assigned_user_id' => null , 'active' => true ] ,
//     [ 'assigned_place_id' => 'A1' , 'header' => 'javascript injection' , 
//         'description' => '<script>alert("du er blevet hacket");</script>' , 
//         'ticket_status_id' => 'OK'  , 'assigned_user_id' => null , 'active' => true ] ,
    [ 'assigned_place_id' => 'C3' , 'header' => 'Alle telefonmaster mangler elekticitet' , 'description' => '' , 'ticket_status_id' => 'Tekniker Tilkald'  , 'assigned_user_id' => null , 'active' => false ]
] ;

// $teknikere = [ 'casp7654'  , 'LarsL' , 'kriskris' , 'MichaleM' , 'toke1254' , 'JanJ' , 'skp-IT' ] ;

$teknikere = [] ;
foreach ( ( new Users() ) as $user ) 
    $teknikere[] = $user->getData()['username'] ;
unset( $teknikere[0] ) ;
$teknikere[] = null ;

foreach ( $tickets as $key => $ticket )
{
print_r( $ticket ) ;
    preg_match('/(.)(.*)/', $ticket['assigned_place_id'] , $where ) ;
    $area  = new Area( 'name' , $where[1] ) ;
    $place = new Place( ['place_nr','area_id'] , [$where[2],$area->getData()['id']] ) ;
    $tickets[ $key ]['assigned_place_id'] = $place->getData()['id'] ;
    $ticketStatus = new TicketStatus( 'name' , $ticket['ticket_status_id'] ) ;
    $tickets[ $key ]['ticket_status_id'] = $ticketStatus->getData()['id'] ;
    if ( ! is_null( $teknikere[ $key % count( $teknikere ) ] ) )
    {
        $user = new User( 'username' , $teknikere[ $key % count( $teknikere ) ] ) ;
        $tickets[ $key ]['assigned_user_id'] = $user->getData()['id'] ;
    }
    $tickets[ $key ]['description'] = 'Excepteur sint occaecat cupidatat non proident' ;
}   unset ( $key , $ticket ) ;

// $tickets[7]['description'] = '<script>alert("du er blevet hacket");</script>' ;

/*
 *  main
 */
( new Tickets() )->deleteAll() ;

foreach ( $tickets as $key => $ticket )
{
    $activeTicket = new Ticket( $ticket ) ;
sleep( 2 ) ; }

$allTickets = new Tickets() ;
foreach ( $allTickets as $ticket )
    echo json_encode( $ticket->getData() , JSON_UNESCAPED_UNICODE ) . \PHP_EOL ;
    unset( $ticket , $allTickets ) ;

echo '</pre>' ;
?>

