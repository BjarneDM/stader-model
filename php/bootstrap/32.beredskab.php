<?php   namespace Stader\Bootstrap ;

echo '<pre>' ;
echo \PHP_EOL . str_repeat( '-' , 50 ) . \PHP_EOL . '-> entering : ' . basename( __file__ ) . \PHP_EOL ;

/*

create table if not exists beredskab
(
    id              int auto_increment primary key ,
    message         text not null ,
    header          text ,
    created_by_id   int not null ,
        foreign key (created_by_id) references user(id)
        on update cascade 
        on delete restrict ,
    active          boolean default true ,
    creationtime    datetime
        default current_timestamp ,
    colour          varchar(16) default 'red' ,
    flag            varchar(255) default null
) ;

 */

/*
 *  setup
 */

require_once( dirname( __dir__ ) . '/classloader.php' ) ;
use \Stader\Control\Objects\User\{User} ;
use \Stader\Model\Tables\Beredskab\{Beredskabs,Beredskab} ;

/*
 *  data
 */

//     [ 'message' => '' , 'header' => '' , 'created_by_id' => '' ] ,
$beredskaber =
[
    [ 'header' => 'LynNedslag i hovedtransformer' , 'message' => '' , 'created_by_id' => 'lani' ] ,
    [ 'header' => 'Pizza til alle kl 18:00' , 'message' => '' , 'created_by_id' => 'last' ] ,
    [ 'header' => 'Brand hos EnhedsListen' , 'message' => '' , 'created_by_id' => 'lani' ] ,
    [ 'header' => '!!! StormVarsel : vindstyrke 10 fra 20:00 !!!' , 'message' => '' , 'created_by_id' => 'last' ]
] ;

/*
 *   main
 */

( new Beredskabs() )->deleteAll() ;

foreach ( $beredskaber as $beredskab )
{
    $user = new User( 'username' , $beredskab['created_by_id'] ) ;
    $beredskab['created_by_id'] = $user->getData()['id'] ;
    $emergency = new Beredskab( $beredskab ) ;
} unset( $beredskab ) ;

foreach ( ( new Beredskabs() ) as $alarm ) 
    echo json_encode( $alarm->getData() , JSON_UNESCAPED_UNICODE ) . \PHP_EOL ;
    unset( $alarm ) ;

echo '</pre>' ;
?>
