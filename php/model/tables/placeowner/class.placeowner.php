<?php namespace stader\model ;

/*

create table if not exists placeowner
(
    id              int auto_increment primary key ,
    name            varchar(255) not null ,
    surname         varchar(255) not null ,
    phone           varchar(255) not null ,
    email           varchar(255) not null ,
    organisation    varchar(255) not null
) ;

 */

class PlaceOwner extends ObjectDao
{
    public static $allowedKeys = 
        [ 'name'         => 'varchar' , 
          'surname'      => 'varchar' , 
          'phone'        => 'varchar' , 
          'email'        => 'varchar' , 
          'organisation' => 'varchar' 
        ] ;
    protected     $class       = '\\stader\\model\\PlaceOwner' ;

    function __construct ( ...$args )
    {   // echo 'class PlaceOwner extends ObjectDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
