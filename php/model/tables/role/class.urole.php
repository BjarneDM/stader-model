<?php namespace stader\model ;

/*

create table if not exists urole
(
    id          int auto_increment primary key ,
    role        varchar(255) not null ,
        constraint unique (role) ,
    priority    int ,
        index (priority) ,
    note        text
) ;

 */

class URole extends ObjectDao
{
    public static $allowedKeys = 
        [ 'role'     => 'varchar' , 
          'note'     => 'text'    , 
          'priority' => 'int'
        ] ;
    protected     $class       = '\\stader\\model\\URole' ;

    function __construct ( ...$args )
    {   // echo 'class URole extends ObjectDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys , $args ) ;

        $this->setupData( $args ) ;
        $this->values['priority'] = (int) $this->values['priority'] ;

    }

}

?>
