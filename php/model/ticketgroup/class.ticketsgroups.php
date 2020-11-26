<?php namespace stader\model ;

require_once( __dir__ . '/class.ticketsgroupsdao.php' ) ;

class TicketsGroups extends TicketsGroupsDao
{
    private $allowedKeys = [ 'ticket_id' , 'header' , 'group_id' , 'name' ] ;

    function __construct ( ...$args )
    {   // echo 'class TicketsGroups extends TicketsGroupsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct() ;

        /*
         *  gettype( $args[0] ) === 'null' 
         *      hent alle tickets_groupss
         *      $testTicketsGroups = new TicketsGroupss() ;
         *  gettype( $args[0] ) === 'array' | 'string'
         *      hent alle tickets_groupss på basis af værdierne i $args[0] , $args[1] 
         *      $testTicketsGroups = new TicketsGroupss($args[0] , $args[1] )
         */
        if ( ! isset( $args[0] ) ) { $args = [] ; $args[0] = null ; }
        switch ( strtolower( gettype( $args[0] ) ) )
        {
            case 'null' :
                $this->readAll() ;
                break ;
            case 'string' :
                if ( strtolower( gettype( $args[1] ) ) !== 'integer' )
                    throw new \Exception( gettype( $args[1] ) . ' er ikke "integer"' ) ;
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
