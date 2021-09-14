<?php   namespace stader\bootstrap ;

echo '<pre>' ;
echo \PHP_EOL . str_repeat( '-' , 50 ) . \PHP_EOL . '-> entering : ' . basename( __file__ ) . \PHP_EOL ;



/*

create table in not exists type_byte
(
    type_byte_id        int auto_increment primary key ,
    name                varchar(255) ,
        constraint unique (name)
)

 */


/*
 *  setup
 */

require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;
use \stader\model\{TypeByte,TypeBytes} ;

/*
 *  data
 */

$typebytes =
[
    [  'name' => 'DEFAULT' ] ,
    [  'name' => 'Opsæt' ] ,
    [  'name' => 'Afvikling' ] ,
    [  'name' => 'Nedtag' ] 
] ;

/*
 *  main
 */

( new TypeBytes() )->deleteAll() ;

foreach ( $typebytes as $key => $typebyte )
{
    $thisTypeByte = new TypeByte( $typebyte ) ;
}   unset( $key , $typebyte ) ;

foreach ( ( new TypeBytes() ) as $typebyte )
    echo json_encode( $typebyte->getData() , JSON_UNESCAPED_UNICODE ) . \PHP_EOL ;
    unset( $typebyte , $allTypeBytes ) ;

echo '</pre>' ;
?>
