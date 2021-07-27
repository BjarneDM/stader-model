<?php   namespace stader\bootstrap ;

echo \PHP_EOL . str_repeat( '-' , 50 ) . \PHP_EOL . '-> entering : ' . basename( __file__ ) . \PHP_EOL ;


require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;

/*

create table if not exists ticket_status
(
    id              int auto_increment primary key,
    name            varchar(255) ,
        constraint unique (name) ,
    default_colour  varchar(255) ,
    description     text ,
    type_byte_id    int ,
        foreign key (type_byte_id) references type_byte(id)
        on update cascade
        on delete restrict
) ;

 */


/*
 *  setup
 */

use \stader\model\{TicketStatuses,TicketStatus,TypeBytes,TypeByte} ;

/*
 *  data
 */

//     [  'name' => '' , 'default_colour' => '' , 'description' => '' , 'type_byte_id' => '' ] ,
$ticketstatuss =
[
//     [  'name' => 'OK' , 'default_colour' => 'green' , 'description' => '' , 'type_byte_id' => 'Afvikling' ] ,
//     [  'name' => 'AKUT' , 'default_colour' => 'red' , 'description' => 'LØB - DET HASTER' , 'type_byte_id' => 'Afvikling' ] ,
//     [  'name' => 'Fejl' , 'default_colour' => 'yellow' , 'description' => '' , 'type_byte_id' => 'Afvikling' ] ,
//     [  'name' => 'Fyld data' , 'default_colour' => 'blue' , 'description' => '' , 'type_byte_id' => 'Afvikling' ] ,
//     [  'name' => 'Rettet mangler' , 'default_colour' => 'blue' , 'description' => '' , 'type_byte_id' => 'Afvikling' ] 

    [  'name' => 'Pakning' , 'default_colour' => 'black' , 'description' => 'Klargør Ordren' , 'type_byte_id' => 'Opsæt' ] ,
    [  'name' => 'Transport' , 'default_colour' => 'grey' , 'description' => '' , 'type_byte_id' => 'Opsæt' ] ,
    [  'name' => 'Opsætning' , 'default_colour' => 'lightblue' , 'description' => '' , 'type_byte_id' => 'Opsæt' ] ,
    [  'name' => 'Kunde Godkendelse' , 'default_colour' => 'darkblue' , 'description' => '' , 'type_byte_id' => 'Opsæt' ] ,
    [  'name' => 'Godkendt' , 'default_colour' => 'white' , 'description' => '' , 'type_byte_id' => 'Opsæt' ] ,
    [  'name' => 'Opstart Morgen' , 'default_colour' => 'green' , 'description' => 'Klar til brug' , 'type_byte_id' => 'Afvikling' ] ,
    [  'name' => 'Runderings Kontrol' , 'default_colour' => 'yellow' , 'description' => '(mere end 120 minutter siden sidste opgave opdatering)' , 'type_byte_id' => 'Afvikling' ] ,
    [  'name' => 'Tekniker Tilkald' , 'default_colour' => 'yellow' , 'description' => '' , 'type_byte_id' => 'Afvikling' ] ,
    [  'name' => 'Mangler Udstyr' , 'default_colour' => 'orange' , 'description' => 'Husk at skrive med note om behov' , 'type_byte_id' => 'Afvikling' ] ,
    [  'name' => 'AKUT fejlmelding' , 'default_colour' => 'red' , 'description' => 'LØB - DET HASTER' , 'type_byte_id' => 'Afvikling' ] ,
    [  'name' => 'Klar til transport - fra Base' , 'default_colour' => 'grey' , 'description' => '' , 'type_byte_id' => 'Afvikling' ] ,
    [  'name' => 'Mangler opsætning' , 'default_colour' => 'orange' , 'description' => 'Klar til tekniker' , 'type_byte_id' => 'Afvikling' ] ,
    [  'name' => 'Tekniker på vej' , 'default_colour' => 'lightgreen' , 'description' => 'Til kunden' , 'type_byte_id' => 'Afvikling' ] ,
    [  'name' => 'Afvikles' , 'default_colour' => 'darkgreen' , 'description' => '' , 'type_byte_id' => 'Afvikling' ] ,
    [  'name' => 'OK' , 'default_colour' => 'green' , 'description' => '' , 'type_byte_id' => 'Afvikling' ] ,
    [  'name' => 'Sidste event' , 'default_colour' => 'purple' , 'description' => '(IOndtast tidspunkt og evt. dato) med Note' , 'type_byte_id' => 'Afvikling' ] ,
    [  'name' => 'Klar til nedtagning' , 'default_colour' => 'purple' , 'description' => 'Slut' , 'type_byte_id' => 'Nedtag' ] ,
    [  'name' => 'Transport - til Base' , 'default_colour' => 'lightgrey' , 'description' => '' , 'type_byte_id' => 'Nedtag' ] ,
    [  'name' => 'Afleveret i Base' , 'default_colour' => 'grey' , 'description' => '' , 'type_byte_id' => 'Nedtag' ] ,
    [  'name' => 'Afsluttet' , 'default_colour' => 'black' , 'description' => 'Inaktiv' , 'type_byte_id' => 'Nedtag' ]
] ;

/*
 *  main
 */

( new TicketStatuses() )->deleteAll() ;

foreach ( $ticketstatuss as $key => $ticketstatus )
{
    $thisTypeByte = new TypeByte( 'name' , $ticketstatus['type_byte_id']  ) ;
    $ticketstatus['type_byte_id'] = $thisTypeByte->getData()['id'] ;
    $thisTicketStatus = new TicketStatus( $ticketstatus ) ;
}   unset( $key , $ticketstatus ) ;

$allTicketStatuses = new TicketStatuses() ;
foreach ( $allTicketStatuses->getAll() as $ticketstatus )
    echo json_encode( $ticketstatus->getData() , JSON_UNESCAPED_UNICODE ) . \PHP_EOL ;
    unset( $ticketstatus , $allTicketStatuses ) ;

?>
