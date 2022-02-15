<?php namespace Stader\Model ;

use \Stader\Model\Abstract\LogObjectsDao ;

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

class BeredskabLogs extends LogObjectsDao
{
    public static $allowedKeys = 
        [ 'beredskab_id' => 'int'     , 
          'header'       => 'varchar' , 
          'old_value'    => 'text'    , 
          'new_value'    => 'text'
        ] ;
    protected $class = '\\Stader\\Model\\Tables\\Beredskab\\BeredskabLog' ;

    function __construct ( ...$args )
    {   // echo 'class BeredskabLogss extends BeredskabLogssDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
