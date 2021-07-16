<?php namespace stader\model ;

require_once( __dir__ . '/class.ticketsdao.php' ) ;

class Tickets extends TicketsDao
{
    private $allowedKeys = [ 'header' , 'description' , 'assigned_place_id' , 'ticket_status_id' , 'assigned_user_id' , 'active' ] ;

    function __construct ( ...$args )
    {   // echo 'class Tickets extends TicketsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct() ;

        /*
         *  gettype( $args[0] ) === 'null' 
         *      hent alle users
         *      $testTickets = new Tickets() ;
         *  gettype( $args[0] ) === 'array' | 'string'
         *      hent alle users på basis af værdierne i $args[0] , $args[1] 
         *      $testTickets = new Tickets($args[0] , $args[1] )
         */
        if ( ! isset( $args[0] ) ) { $args = [] ; $args[0] = null ; }
        switch ( strtolower( gettype( $args[0] ) ) )
        {
            case 'null' :
                // echo count($args ) . " : " . gettype( $args[0] ) . \PHP_EOL ;
                $this->readAll() ;
                break ;
            case 'string' :
                // echo count($args ) . " : " . gettype( $args[0] ) . \PHP_EOL ;
                if ( strtolower( gettype( $args[1] ) ) !== 'string' )
                    throw new \Exception( gettype( $args[0] ) . ' & ' . gettype( $args[1] ) . ' er ikke begge "string"' ) ;
                 if ( ! in_array( $args[0] , $this->allowedKeys ) )
                    throw new \Exception( "'{$key}' doesn't exist in [ " . implode( ' , ' , $this->allowedKeys ) . " ]" ) ;
                $this->readAll( $args[0] , $args[1] ) ;
                break ;
            case 'array' :
                // echo count($args ) . " : " . gettype( $args[0] ) . \PHP_EOL ;
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
