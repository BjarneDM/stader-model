<?php   namespace stader\bootstrap ;

echo '<pre>' ;
echo \PHP_EOL . str_repeat( '-' , 50 ) . \PHP_EOL . '-> entering : ' . basename( __file__ ) . \PHP_EOL ;



/*

create table user_groups
(
    group_id    int auto_increment primary key ,
    name        varchar(255) not null ,
        constraint unique (name) ,
    description varchar(255) 
) ;

 */


/*
 *  setup
 */

require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;
use \stader\model\{Group,Groups} ;

/*
 *  data
 */

$groups =
[
    [  'name' => 'BackOffice' , 'description' => 'Admin' ] ,
    [  'name' => 'Tlf.Sup' , 'description' => 'Fjernsupport (modtager af Fejlmeldinger)' ] ,
    [  'name' => 'Klargøring' , 'description' => 'Pakke udstyr i basen' ] ,
    [  'name' => 'Transport' , 'description' => 'Sørger for at udstyr køres fra base til stadeplads' ] ,
    [  'name' => 'Runderings Tekniker' , 'description' => '' ] ,
    [  'name' => 'Support Tekniker' , 'description' => 'Løser fejl/opgaver (assistent til Rundereings Tekniker)' ] 

//     [  'name' => 'Backend' , 'description' => 'mail support' ] ,
//     [  'name' => 'Data' , 'description' => 'mail support' ] ,
//     [  'name' => 'Fejl 40' , 'description' => 'normal besked' ] 
//     [  'name' => 'Frontend' , 'description' => 'Admin' ] ,
//     [  'name' => 'Logic' , 'description' => 'Fjernsupport (modtager af Fejlmeldinger)' ] ,
] ;

/*
 *  main
 */

foreach ( $groups as $key => $group )
{
    $activeGroup = new Group( $group ) ;
}   unset( $key , $group ) ;

$allGroups = new Groups() ;
foreach ( $allGroups->getGroups() as $group )
    echo json_encode( $group->getData() , JSON_UNESCAPED_UNICODE ) . \PHP_EOL ;
    unset( $group , $allGroups ) ;

echo '</pre>' ;
?>
