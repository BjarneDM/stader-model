<?php namespace stader\eksempler ;

/*

create table if not exists tickets
(
    ticket_id           int auto_increment primary key ,
    header              varchar(255) not null ,
    description         text not null ,
    assigned_place_id   int ,
        foreign key (assigned_place_id) references places(place_id)
        on update cascade 
        on delete restrict ,
    ticket_status_id    int not null ,
        foreign key (ticket_status_id) references ticket_status(ticket_status_id)
        on update cascade 
        on delete restrict ,
    assigned_user_id    int default null ,
        foreign key (assigned_user_id) references userscrypt(user_id)
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
   $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/stader/php' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/cdn/php' ;
// $include_paths[] =  '.' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/cdn/_/php' ;
set_include_path( implode( ':' , $include_paths ) ) ;

// echo 'IncludePaths : ' . \PHP_EOL ;
// print_r( explode( ':' , get_include_path() ) ) ;

require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;

use \stader\model\{Ticket,Tickets} ;

$keys = [ 'header' , 'assigned_place_id' ] ;
$vals = [ 'brand i transformer' , 41 ] ;
// $keys =   'header' ;
// $vals =   'brand i transformer'   ;

$problemer = new Tickets( $keys , $vals ) ;
print_r( $problemer ) 

?>
