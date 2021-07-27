<?php namespace stader\model ;

/*

create table if not exists ugroup
(
    id          int auto_increment primary key ,
    name        varchar(255) not null ,
        constraint unique (name) ,
    description varchar(255) 
) ;

 */

class UGroup extends ObjectDao
{
    public static $allowedKeys = 
        [ 'name'        => 'varchar' , 
          'description' => 'varchar'
        ] ;
    protected     $class       = '\\stader\\model\\UGroup' ;

    function __construct ( ...$args )
    {   // echo 'class UGroup extends ObjectDao __construct' . \PHP_EOL ;
        // var_dump( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys , $args ) ;

        $this->setupData( $args ) ;

    }

}

?>
