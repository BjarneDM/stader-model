<?php   namespace Stader\Bootstrap ;

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

require_once( dirname( __dir__ ) . '/classloader.php' ) ;
use \Stader\Control\Objects\User\{User,Users} ;
use \Stader\Model\Tables\Role\{URole,URoles} ;
use \Stader\Model\Tables\UserRole\{UserRole,UsersRoles} ;

/*
 *  data
 */


/*
 *   main
 */
( new UsersRoles() )->deleteAll() ;

$specUsers =
[
    'ejer'  => [ 'lani' ] ,
    'admin' => [ 'lani' , 'last' ] 
] ;

foreach ( ( new Users() ) as $user )
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

foreach ( ( new Users() ) as $user )
{
    print_r($user->getData()) ;
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
