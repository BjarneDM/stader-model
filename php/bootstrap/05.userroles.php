<?php   namespace stader\bootstrap ;

echo '<pre>' ;
echo \PHP_EOL . str_repeat( '-' , 50 ) . \PHP_EOL . '-> entering : ' . basename( __file__ ) . \PHP_EOL ;


/*

create table if not exists userrole
(
    id          int auto_increment primary key ,
    user_id     int ,
        foreign key (user_id) references user(id)
        on update cascade 
        on delete cascade ,
    role_id     int ,
        foreign key (role_id) references urole(id)
        on update cascade 
        on delete cascade
) ;

 */

/*
 *  setup
 */

require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;
use \stader\model\{User,Users,URole,URoles,UserRole,UsersRoles} ;

/*
 *  data
 */


/*
 *   main
 */
( new UsersRoles() )->deleteAll() ;

$brugere = new Users() ;

$specUsers =
[
    'ejer'  => [ 'lani' ] ,
    'admin' => [ 'lani' , 'last' ] 
] ;

foreach ( $brugere as $user )
{
    $priv = new UserRole
    (
        [
            'user_id'   => $user->getData()['id'] ,
            'role'      => 'user'
        ]
    ) ;
    
}   unset( $user ) ;

foreach ( $specUsers as $level => $users )
{
    foreach ( $users as $username )
    {
        $priv = new UserRole
        (
            [
                'username'  => $username ,
                'role'      => $level
            ]
        ) ;
    }   unset( $username ) ;
}   unset( $users , $level ) ;

foreach ( $brugere as $user )
{
        $roller = new UsersRoles( 'user_id' , $user->getData()['id'] ) ;
        $rollerne = [] ;
        foreach ( $roller as $rolle )
        {
            $rollerne[] = ( new URole( $rolle->getData()['role_id'] ) )->getData()['role'] ;
        }   unset( $rolle ) ;
        echo $user->getData()['username'] . ' : [ ' . implode( ' , ' ,  $rollerne )  . ' ]' . \PHP_EOL ;
}   unset( $user ) ;

echo '</pre>' ;
?>
