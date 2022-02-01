<?php namespace Stader\Model ;

/*

create table if not exists beredskab_log
(
    id              int auto_increment primary key ,
    header          varchar(255) ,
    beredskab_id       int ,
    log_timestamp   datetime
        default current_timestamp ,
    data            text
) ;

 */

require_once( __dir__ . '/class.beredskablogsdao.php' ) ;

class BeredskabLogs extends ObjectsDao
{
    public static $allowedKeys = 
        [ 'beredskab_id' => 'int'     , 
          'header'       => 'varchar' , 
          'old_value'    => 'text'    , 
          'new_value'    => 'text'
        ] ;
    protected     $class       = '\\stader\\model\\BeredskabLog' ;

    function __construct ( ...$args )
    {   // echo 'class BeredskabLogss extends BeredskabLogssDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'logs' , self::$allowedKeys , $args ) ;

        $this->setupData( $args ) ;

    }

}

?>
