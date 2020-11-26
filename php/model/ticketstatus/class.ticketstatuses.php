<?php namespace stader\model ;

require_once( __dir__ . '/class.ticketstatusesdao.php' ) ;

class TicketStatuses extends TicketStatusesDao
{
    private $allowedKeys = [ 'name' , 'default_colour' , 'description' , 'type_byte_id' ] ;

    function __construct ( ...$args )
    {   // echo 'class TicketStatuses extends TicketStatusesDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct() ;

        /*
         *  gettype( $args[0] ) === 'null' 
         *      hent alle ticket_statuses
         *      $testTicketStatuses = new TicketStatuses() ;
         *  gettype( $args[0] ) === 'array' | 'string'
         *      hent alle ticket_statuses på basis af værdierne i $args[0] , $args[1] 
         *      $testTicketStatuses = new TicketStatuses($args[0] , $args[1] )
         */
        if ( ! isset( $args[0] ) ) { $args = [] ; $args[0] = null ; }
        switch ( strtolower( gettype( $args[0] ) ) )
        {
            case 'null' :
                $this->readAll() ;
                break ;
            case 'string' :
                if ( strtolower( gettype( $args[1] ) ) !== 'string' )
                    throw new \Exception( gettype( $args[0] ) . ' & ' . gettype( $args[1] ) . ' er ikke begge "string"' ) ;
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
