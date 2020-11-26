<?php namespace stader\model ;

require_once( __dir__ . '/class.ticketdaopdo.php' ) ;
require_once( dirname( __file__ , 2 ) . '/connect/class.setup.php' ) ;

class TicketsDao extends Setup
{
    private   $functions = null ;
    protected $tickets = [] ;
    
    function __construct ()
    {   // echo 'class TicketsDao extends Setup __construct' . \PHP_EOL ;

        parent::__construct() ;

        switch ( self::$connect->getType() )
        {
            case "mysql"    : $this->functions = new TicketDaoPdo( self::$connect ) ; break ;
            case "pgsql"    : $this->functions = new TicketDaoPdo( self::$connect ) ; break ;
            case "sqlite"   : $this->functions = new TicketDaoPdo( self::$connect ) ; break ;
            case "xml"      : $this->functions = new TicketDaoXml( self::$connect ) ; break ;
            default: throw new \Exception() ;
            // var_dump( $this->functions ) ;
        } 

    }

    protected function readAll( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $ticketIds = $this->functions->readAll( ...$args ) ;
        foreach ( $ticketIds as $ticketId )
        {
            $this->tickets[] = new Ticket( (int) $ticketId ) ;
        }
        reset( $this->tickets ) ;
    }

    public function count()     { return   count( $this->tickets ) ; }
    public function reset()     { return   reset( $this->tickets ) ; }
    public function prev()      { return    prev( $this->tickets ) ; }
    public function current()   { return current( $this->tickets ) ; }
    public function next()      { return    next( $this->tickets ) ; }
    public function end()       { return     end( $this->tickets ) ; }

    public function getTicket( int $index ) { return $this->tickets[ $index ] ; }
    public function getOne( int $index ) { return $this->getTicket( $index ) ; }

    public function getTickets() { return $this->tickets ; }
    public function getAll() { return $this->getTickets() ; }
}

?>
