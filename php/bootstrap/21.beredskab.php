<?php   namespace stader\bootstrap ;

echo '<pre>' ;
echo \PHP_EOL . str_repeat( '-' , 50 ) . \PHP_EOL . '-> entering : ' . basename( __file__ ) . \PHP_EOL ;


/*

create table if not exists beredskab
(
    beredskab_id    int auto_increment primary key ,
    message         text not null ,
    note            text ,
    user_id         int not null ,
        foreign key (user_id) references userscrypt(user_id)
        on update cascade 
        on delete restrict ,
) ;

 */

/*
 *  setup
 */

require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;
use \stader\model\{User,Beredskabs,Beredskab} ;

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

foreach ( $beredskaber as $beredskab )
{
    $user = new User( 'username' , $beredskab['created_by_id'] ) ;
    $beredskab['created_by_id'] = $user->getData()['user_id'] ;
    $emergency = new Beredskab( $beredskab ) ;
} unset( $beredskab ) ;

$allBeredskabs = new Beredskabs() ;
foreach ( $allBeredskabs->getBeredskabs() as $alarm ) 
    echo json_encode( $alarm->getData() , JSON_UNESCAPED_UNICODE ) . \PHP_EOL ;
    unset( $alarm ) ;

echo '</pre>' ;
?>
