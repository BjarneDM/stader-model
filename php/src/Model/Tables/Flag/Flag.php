<?php namespace Stader\Model\Tables\Flag ;

use \Stader\Model\Abstract\ObjectDao ;

/*

create table if not exists flag
(
    id          int auto_increment primary key ,
    text        varchar(255) ,
        constraint unique (text) ,     
    unicode     char(6) 
) ;

 */

class Flag extends ObjectDao
{
    public static $allowedKeys = 
        [ 'text'    => 'varchar' , 
          'unicode' => 'char'
        ] ;
    protected   $class  = '\\Stader\\Model\\Tables\\Flag\\Flag' ;

    function __construct ( ...$args )
    {   // echo 'class Flag extends ObjectDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
