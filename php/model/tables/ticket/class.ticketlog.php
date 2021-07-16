<?php namespace stader\model ;

/*

create table if not exists ticket_log
(
    ticket_log_id   int auto_increment primary key ,
    header          varchar(255) ,
    ticket_id       int ,
    log_timestamp   datetime
        default current_timestamp ,
    data            text
) ;

 */

require_once( __dir__ . '/class.ticketlogdao.php' ) ;

class TicketLog extends TicketLogDao
{
    private $allowedKeys = [ 'ticket_id' , 'header' , 'old_value' , 'new_value' ] ;

    function __construct ( ...$args )
    {   // echo 'class TicketLog extends TicketLogDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct() ;

        /*
         *  gettype( $args[0] ) === 'array'
         *      opret en ticket_log på basis af værdierne i $args[0]
         *      $testTicketLog = new TicketLog( $newTicketLog )
         */
        switch ( count( $args ) )
        {
            case 1 :
                switch ( strtolower( gettype( $args[0] ) ) )
                {
                    case 'integer' :
                        $this->read( $args[0] ) ;
                        break ;
                    case 'array' :
                        /*
                         *  count( $args[0] ) === 4 : ny ticket_log, der skal oprettes
                         */
                        switch ( count( $args[0] ) )
                        {
                            case 4 :
                                $this->check( $args[0] ) ;
                                $this->create( $args[0] ) ;
                                break ;
                            default :
                                throw new \Exception( count( $args[0] ) . " : forkert antal parametre [4]" ) ;
                                break ;
                        }
                        break ;
                    default :
                        throw new \Exception( gettype( $args[0] ) . " : forkert input type [integer,array]" ) ;
                        break ;
                }
                break ;
            default :
                throw new \Exception( count( $args ) . " : forkert antal parametre [1]" ) ;
                break ;
        }
    }

    private function check( Array &$toCheck )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $toCheck ) ;

        foreach ( array_keys( $toCheck ) as $key )
        {
            if ( ! in_array( $key , $this->allowedKeys ) )
                throw new \Exception( "'{$key}' doesn't exist in [ " . implode( ' , ' , $this->allowedKeys ) . " ]" ) ;

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
                case 'ticket_id' :
                    if ( strtolower( gettype( $toCheck[ $key ] ) ) !== 'integer' )
                        throw new \Exception( gettype( $toCheck[ $key ] ) . " : forkert input type for {$key} [integer]" ) ;
                    break ;
            }
        }
    }

}

?>
