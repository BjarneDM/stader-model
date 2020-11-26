<?php   namespace stader\bootstrap ;

echo '<pre>' ;
echo \PHP_EOL . str_repeat( '-' , 50 ) . \PHP_EOL . '-> entering : ' . basename( __file__ ) . \PHP_EOL ;


/*

create table if not exists roles
(
    role_id     int auto_increment primary key ,
    role        varchar(255) not null ,
        constraint unique (name) ,
    note        text
) ;

 */

/*
 *  setup
 */

require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;
use \stader\model\{Role,Roles} ;

/*
 *  data
 */

//     [ 'role' => '' , 'note' => '' , 'priority' => ] ,
$roles =
[
    [ 'role' => 'ejer'  , 'note' => '' , 'priority' => 1024 ] ,
    [ 'role' => 'admin' , 'note' => '' , 'priority' =>  512 ] ,
    [ 'role' => 'user'  , 'note' => '' , 'priority' =>  256 ]
] ;

/*
 *   main
 */

foreach ( $roles as $role )
{
    $thisRole = new role( $role ) ;
} unset( $role ) ;

$allRoles = new Roles() ;
foreach ( $allRoles->getRoles() as $role ) 
    echo json_encode( $role->getData() , JSON_UNESCAPED_UNICODE ) . \PHP_EOL ;
    unset( $role ) ;

echo '</pre>' ;
?>
