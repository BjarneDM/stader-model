<?php namespace stader\model ;

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
    protected     $class       = '\\stader\\model\\Flag' ;

    function __construct ( ...$args )
    {   // echo 'class Flag extends ObjectDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys , $args ) ;

        $this->setupData( $args ) ;

    }

}

?>
