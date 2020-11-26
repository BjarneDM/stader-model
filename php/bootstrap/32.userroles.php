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
use \stader\model\{User,Users,Role,Roles,UserRole,UsersRoles} ;

/*
 *  data
 */


/*
 *   main
 */

$brugere = new Users() ;
$roller  = new Roles() ;

$specUsers =
[
    'ejer'  => [ 'lani' ] ,
    'admin' => [ 'lani' , 'last' ] 
] ;

foreach ( $brugere->getUsers() as $user )
{
    $priv = new UserRole
    (
        [
            'user_id'   => $user->getData()['user_id'] ,
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

foreach ( $brugere->getUsers() as $user )
{
        $roller = new UsersRoles( 'user_id' , $user->getData()['user_id'] ) ;
        $rollerne = [] ;
        foreach ( $roller->getUsersRoles() as $rolle ) 
        {
            $rollerne[] = ( new Role( $rolle->getData()['role_id'] ) )->getData()['role'] ;
        }   unset( $rolle ) ;
        echo $user->getData()['username'] . ' : [ ' . implode( ' , ' ,  $rollerne )  . ' ]' . \PHP_EOL ;
}   unset( $user ) ;

echo '</pre>' ;
?>
