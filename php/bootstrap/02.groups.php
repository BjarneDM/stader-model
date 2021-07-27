<?php   namespace stader\bootstrap ;

echo '<pre>' ;
echo \PHP_EOL . str_repeat( '-' , 50 ) . \PHP_EOL . '-> entering : ' . basename( __file__ ) . \PHP_EOL ;



/*

create table if not exists ugroup
(
    id          int auto_increment primary key ,
    name        varchar(255) not null ,
        constraint unique (name) ,
    description varchar(255) 
) ;

 */


/*
 *  setup
 */

require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;
use \stader\model\{UGroup,UGroups} ;

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

( new UGroups() )->deleteAll() ;

foreach ( $groups as $key => $group )
{
    $activeGroup = new UGroup( $group ) ;
}   unset( $key , $group ) ;

$allGroups = new UGroups() ;
foreach ( $allGroups->getAll() as $group )
    echo json_encode( $group->getData() , JSON_UNESCAPED_UNICODE ) . \PHP_EOL ;
    unset( $group ) ;

echo \PHP_EOL . str_repeat( '-' , 50 ) . \PHP_EOL  . \PHP_EOL ;

if ( $allGroups->count() > 0 )
{
    $allGroups->reset() ;
    do {
        echo json_encode( $allGroups->current()->getData() , JSON_UNESCAPED_UNICODE ) . \PHP_EOL ;
    }   while ( $allGroups->next() ) ;
}

echo '</pre>' ;
?>
