<?php namespace stader\model ;

/*

create table if not exists beredskab_log
(
    beredskab_log_id   int auto_increment primary key ,
    header          varchar(255) ,
    beredskab_id       int ,
    log_timestamp   datetime
        default current_timestamp ,
    data            text
) ;

 */

require_once( __dir__ . '/class.beredskablogsdao.php' ) ;

class BeredskabLogs extends BeredskabLogsDao
{
    private $allowedKeys = [ 'header' , 'beredskab_log_id' , 'beredskab_id' ] ;

    function __construct ( ...$args )
    {   // echo 'class BeredskabLogss extends BeredskabLogssDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct() ;

        /*
         *  gettype( $args[0] ) === 'null' 
         *      hent alle users
         *      $testBeredskabLogs = new BeredskabLogs() ;
         *  gettype( $args[0] ) === 'array' | 'string'
         *      hent alle users på basis af værdierne i $args[0] , $args[1] 
         *      $testBeredskabLogs = new BeredskabLogs($args[0] , $args[1] )
         */
        if ( ! isset( $args[0] ) ) { $args = [] ; $args[0] = null ; }
        switch ( strtolower( gettype( $args[0] ) ) )
        {
            case 'null' :
                $this->readAll() ;
                break ;
            case 'string' :
                 if ( ! in_array( $args[0] , $this->allowedKeys ) )
                    throw new \Exception( "'{$key}' doesn't exist in [ " . implode( ' , ' , $this->allowedKeys ) . " ]" ) ;
                $this->readAll( $args[0] , $args[1] ) ;
                break ;
            case 'array' :
                if ( count( $args[0] ) !== count( $args[1] ) )
                    throw new \Exception( 'count() for $args[0] & $args[1] er forskellige' ) ;
                foreach ( $args[0] as $key )
                {
                    if ( ! in_array( $key , $this->allowedKeys ) )
                        throw new \Exception( "'{$key}' doesn't exist in [ " . implode( ' , ' , $this->allowedKeys ) . " ]" ) ;
                }
                $this->readAll( $args[0] , $args[1] ) ;
                break ;
            default :
                throw new \Exception( gettype( $args[0] ) . " : forkert input type [null,string,array]" ) ;
                break ;
        }
    }

}

?>
