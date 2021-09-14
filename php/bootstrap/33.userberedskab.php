<?php   namespace stader\bootstrap ;

echo '<pre>' ;
echo \PHP_EOL . str_repeat( '-' , 50 ) . \PHP_EOL . '-> entering : ' . basename( __file__ ) . \PHP_EOL ;


/*

create table if not exists user_beredskab
(
    user_beredskab_id   int auto_increment primary key ,
    user_id             int ,
        foreign key (user_id) references userscrypt(user_id)
        on update cascade 
        on delete cascade ,
    beredskab_id        int ,
        foreign key (group_id) references beredskab(beredskab_id)
        on update cascade 
        on delete cascade
) ;

 */

/*
 *  setup
 */

require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;
use \stader\model\{User,Users,Beredskab,Beredskabs,UserBeredskab,UsersBeredskabs} ;

/*
 *  data
 */


/*
 *   main
 */

( new UsersBeredskabs() )->deleteAll() ;

foreach ( ( new Users() ) as $user )
{
    foreach ( ( new Beredskabs() ) as $alarm )
    {
        $harSet = 
            new UserBeredskab
            ( 
                [
                    'user_id'       => $user->getData()['id'] ,
                    'beredskab_id'  => $alarm->getData()['id']
                ]
            ) ;
//         print_r( $harSet->getData() ) ;
    }   unset( $alarm ) ;
}   unset( $user ) ;

foreach ( ( new UsersBeredskabs() ) as $harSet )
{
    $user  = new User( $harSet->getData()['user_id'] ) ;
    $alarm = new Beredskab( $harSet->getData()['beredskab_id'] ) ;
    $besked  = implode( ' ' , [ $user->getData()['name'] , $user->getData()['surname'] ] ) . " har set " ;
    $besked .= "alarmen : " . $alarm->getData()['message'] ;
    echo $besked . \PHP_EOL ;
}   unset( $harSet ) ;

echo '</pre>' ;
?>
