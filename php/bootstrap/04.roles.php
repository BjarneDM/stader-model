<?php   namespace stader\bootstrap ;

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

require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;
use \stader\model\{URole,URoles} ;

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

$allRoles = new URoles() ;
foreach ( $allRoles->getAll() as $role ) 
    echo json_encode( $role->getData() , JSON_UNESCAPED_UNICODE ) . \PHP_EOL ;
    unset( $role ) ;

echo \PHP_EOL . str_repeat( '-' , 50 ) . \PHP_EOL  . \PHP_EOL ;

if ( $allRoles->count() > 0 )
{
    $allRoles->reset() ;
    do {
        echo json_encode( $allRoles->current()->getData() , JSON_UNESCAPED_UNICODE ) . \PHP_EOL ;
    }   while ( $allRoles->next() ) ;
}

echo '</pre>' ;
?>
