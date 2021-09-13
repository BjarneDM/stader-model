<?php namespace stader\model ;

/*

create table in not exists type_byte
(
    id      int auto_increment primary key ,
    name    varchar(255) ,
        constraint unique (name)
)

 */

class TypeByte extends ObjectDao
{
    public static $allowedKeys = [ 'name' => 'varchar' ] ;
    protected     $class       = '\\stader\\model\\TypeByte' ;

    function __construct ( ...$args )
    {   // echo 'class TypeByte extends ObjectDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
