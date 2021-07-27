<?php namespace stader\model ;

/*

create table if not exists placelog
(
    id              int auto_increment primary key ,
    place_id        int ,
        index (place_id) ,
    header          varchar(255) ,
        index (header) ,
    log_timestamp   datetime
        default current_timestamp ,
        index (log_timestamp) ,
    old_value       text default null ,
    new_value       text default null
) ;

 */

class PlaceLog extends ObjectDao
{
    private $allowedKeys = 
        [ 'place_id'  => 'int'     , 
          'header'    => 'varchar' , 
          'old_value' => 'text'    , 
          'new_value' => 'text'
        ] ;
    protected     $class       = '\\stader\\model\\PlaceLog' ;

    function __construct ( ...$args )
    {   // echo 'class PlaceLog extends ObjectDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'logs' , self::$allowedKeys , $args ) ;

        $this->setupLogs( $args ) ;

    }

}

?>
