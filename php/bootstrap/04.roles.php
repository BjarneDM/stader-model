<?php   namespace Stader\Bootstrap ;

echo '<pre>' ;
echo \PHP_EOL . str_repeat( '-' , 50 ) . \PHP_EOL . '-> entering : ' . basename( __file__ ) . \PHP_EOL ;


/*

create table if not exists urole
(
    id          int auto_increment primary key ,
    role        varchar(255) not null ,
        constraint unique (role) ,
    priority    int ,
        index (priority) ,
    note        text
) ;

 */

/*
 *  setup
 */

require_once( dirname( __dir__ ) . '/classloader.php' ) ;
use \Stader\Model\Tables\Role\{URole,URoles} ;

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
 ( new URoles() )->deleteAll() ;

foreach ( $roles as $role )
{
    $thisRole = new URole( $role ) ;
} unset( $role ) ;

foreach ( ( new URoles() ) as $role ) 
    echo json_encode( $role->getData() , JSON_UNESCAPED_UNICODE ) . \PHP_EOL ;
    unset( $role ) ;

echo '</pre>' ;
?>
