<?php namespace Stader\Model\Tables\TypeByte ;

use \Stader\Model\Abstract\DataObjectDao ;

/*

create table in not exists type_byte
(
    id      int auto_increment primary key ,
    name    varchar(255) ,
        constraint unique (name)
)

 */

class TypeByte extends DataObjectDao
{
    public static $allowedKeys = [ 'name' => 'varchar' ] ;
    protected   $class  = '\\Stader\\Model\\Tables\\TypeByte\\TypeByte' ;

    function __construct ( ...$args )
    {   // echo 'class TypeByte extends ObjectDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
