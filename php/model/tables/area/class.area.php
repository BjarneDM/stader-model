<?php namespace stader\model ;

/*

create table if not exists areas
(
    id          int auto_increment ,
        index(id) ,
    name        varchar(255) not null primary key ,
        constraint unique (name) ,
    description text
) ;

name er primary key da getAll() ønskes sorteret efter denne

 */

class Area extends ObjectDao
{
    public static $allowedKeys = 
        [ 'name'        => 'varchar' , 
          'description' => 'text' 
        ] ;
    protected     $class       = '\\stader\\model\\Area' ;

    public function __construct ( ...$args )
    {   // echo 'class Area extends ObjectDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
