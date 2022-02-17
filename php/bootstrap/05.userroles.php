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
use \Stader\Control\User\{User,Users} ;
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

/*
 *  så dette her fungerer som forventet uden nogen som helst problemer
 */
// foreach ( ( new Users() ) as $user )
// {
//     print_r($user->getData()) ;
// }   unset( $user ) ;

/*
 *  Men dette her fejler på en-eller-anden måde
 *  kun den 1ste User bliver behandlet
 */
// foreach ( ( new Users() ) as $user )
// {
//     print_r($user->getData()) ;
//     /*
//      *  ændres $user->getData()['id'] manuelt, virker det
//      */
//     $roller = new UsersRoles( 'user_id' , $user->getData()['id'] ) ;
//     $rollerne = [] ;
//     foreach ( $roller as $rolle )
//     {
//         $rollerne[] = ( new URole( $rolle->getData()['role_id'] ) )->getData()['role'] ;
//     }   unset( $rolle ) ;
//     echo $user->getData()['username'] . ' : [ ' . implode( ' , ' ,  $rollerne )  . ' ]' . \PHP_EOL ;
// }   unset( $user ) ;

/*
 *  så min kodning kan !!!IKKE!!! lide at have \Iteratorer indeni hinanden
 *  dette work-around fungerer ; men er ikke bruger-venligt
 *  der skal kigges på DB-forbindelserne ...
 */
$ids = [] ;
foreach ( ( new Users() ) as $user )
{
    $ids[] = $user->getData()['id'] ;
}   unset( $user ) ;
foreach ( $ids as $id )
{
    // print_r( ( new User($id) )->getData() ) ;
    $roller = new UsersRoles( 'user_id' , $id ) ;
    $rollerne = [] ;
    foreach ( $roller as $rolle )
    {
        $rollerne[] = ( new URole( $rolle->getData()['role_id'] ) )->getData()['role'] ;
    }   unset( $rolle ) ;
    echo ( new User($id) )->getData()['username'] . ' : [ ' . implode( ' , ' ,  $rollerne )  . ' ]' . \PHP_EOL ;
}   unset( $id ) ;




/*
 *  så lad os prøve noget andet :
 *  den underlæggede id for User kommer fra UserLogin ...
 *  ... & det fungerer heller ikke 
 */
// use \Stader\Model\Tables\User\{UserLogin,UsersLogin} ;
// foreach ( ( new UsersLogin() ) as $user )
// {
//     print_r($user->getData()) ;
//     $roller = new UsersRoles( 'user_id' , $user->getData()['id'] ) ;
//     $rollerne = [] ;
//     foreach ( $roller as $rolle )
//     {
//         $rollerne[] = ( new URole( $rolle->getData()['role_id'] ) )->getData()['role'] ;
//     }   unset( $rolle ) ;
//     echo $user->getData()['username'] . ' : [ ' . implode( ' , ' ,  $rollerne )  . ' ]' . \PHP_EOL ;
// }   unset( $user ) ;

echo '</pre>' ;
?>
