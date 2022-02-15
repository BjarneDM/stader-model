<?php namespace Stader\Model\Tables\Place ;

use \Stader\Model\Abstract\LogObjectDao ;
use \Stader\Model\OurDateTime ;

/*

create table if not exists placelog
(
    id                  int auto_increment primary key ,
    place_id        int ,
        index (place_id) ,
    header              varchar(255) ,
        index (header) ,
    log_timestamp       datetime
        default current_timestamp ,
        index (log_timestamp) ,
    old_value           text default null ,
    new_value           text default null
) ;

 */

class PlaceLog extends LogObjectDao
{
    public static $allowedKeys = 
        [ 'place_id' => 'int'     , 
          'header'       => 'varchar' , 
          'old_value'    => 'text'    , 
          'new_value'    => 'text'
        ] ;
    protected $class = '\\Stader\\Model\\Tables\\Place\\PlaceLog' ;

    function __construct ( ...$args )
    {   // echo 'class PlaceLog extends PlaceLogDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'logs' , self::$allowedKeys ) ;

        $this->setupLogs( $args ) ;
        $this->values['id'] = (int) $this->values['id'] ;
        $this->values['place_id'] = (int) $this->values['place_id'] ;
        $this->values['log_timestamp']  = 
            @is_null( $this->values['log_timestamp'] ) 
            ? new OurDateTime()
            : OurDateTime::createFromFormat( 'Y-m-d H:i:s' , $this->values['creationtime'] ) ;

    }

    protected function check( Array &$toCheck )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $toCheck ) ;

        foreach ( array_keys( $toCheck ) as $key )
        {
            if ( ! array_key_exists( $key , self::$allowedKeys ) )
                throw new \Exception( "'{$key}' doesn't exist in [" . implode( ',' , array_keys( self::$allowedKeys ) ) . "]" ) ;

            switch ( $key )
            {
                case 'old_value' :
                case 'new_value' :
                    if ( ! in_array( strtolower( gettype( $toCheck[ $key ] ) ) , [ 'null' , 'string' ] ) )
                        throw new \Exception( gettype( $toCheck[ $key ] ) . " : forkert input type for {$key} [string]" ) ;
                    break ;
                case 'header' :
                    if ( strtolower( gettype( $toCheck[ $key ] ) ) !== 'string' )
                        throw new \Exception( gettype( $toCheck[ $key ] ) . " : forkert input type for {$key} [string]" ) ;
                    break ;
                case 'place_id' :
                    if ( strtolower( gettype( $toCheck[ $key ] ) ) !== 'integer' )
                        throw new \Exception( gettype( $toCheck[ $key ] ) . " : forkert input type for {$key} [integer]" ) ;
                    break ;
            }
        }
    }

}

?>
