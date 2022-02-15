<?php namespace Stader\Model ;

use \Stader\Model\Abstract\LogObjectsDao ;

/*

create table if not exists place_log
(
    id              int auto_increment primary key ,
    header          varchar(255) ,
    place_id       int ,
    log_timestamp   datetime
        default current_timestamp ,
    data            text
) ;

 */

class PlaceLogs extends LogObjectsDao
{
    public static $allowedKeys = 
        [ 'place_id' => 'int'     , 
          'header'       => 'varchar' , 
          'old_value'    => 'text'    , 
          'new_value'    => 'text'
        ] ;
    protected $class = '\\Stader\\Model\\Tables\\Place\\PlaceLog' ;

    function __construct ( ...$args )
    {   // echo 'class PlaceLogss extends PlaceLogssDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
